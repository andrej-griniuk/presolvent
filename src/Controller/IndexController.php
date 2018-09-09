<?php
namespace App\Controller;

use App\Controller\AppController;
use Phpml\ModelManager;

/**
 * Index Controller
 *
 *
 * @method \App\Model\Entity\Index[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class IndexController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $options = json_decode(file_get_contents(ROOT . DS . 'data' . DS . 'options.json'), true);
        $prediction = null;
        $probabilities = null;

        if ($this->request->is('post')) {
            $this->predict();
        }

        $this->set(compact('options'));
    }

    /**
     * Predict method
     *
     * @return \Cake\Http\Response|void
     */
    public function predict()
    {
        $map = [
            'state' => 2,
            'region' => 1,
            'suburb' => 0,
            'sex' => 3,
            'family-situation' => 4,
            'occupation' => 5,
            'income-source' => 7,
            'income' => 6,
            'debts' => 8,
            'assets' => 9,
        ];

        $features = [];
        if (!$data = $this->request->getData()) {
            $data = $this->request->getQueryParams();
        }
        foreach ($data as $k => $v) {
            if (isset($map[$k])) {
                $features[$map[$k]] = $v;
            }
        }

        $modelManager = new ModelManager();
        /** @var \App\Phpml\Classification\NaiveBayes $estimator */
        $estimator = $modelManager->restoreFromFile(ROOT . DS . 'data' . DS . 'model.dat');

        $prediction = $estimator->predictIt($features);
        $probabilities = $estimator->predictProb($features);

        $this->set(compact('prediction', 'probabilities'));
        $this->set('_serialize', ['prediction', 'probabilities']);
    }

}
