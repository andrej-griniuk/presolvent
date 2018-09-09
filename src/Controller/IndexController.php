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
            $data = $this->request->getData('features');

            $modelManager = new ModelManager();
            /** @var \App\Phpml\Classification\NaiveBayes $estimator */
            $estimator = $modelManager->restoreFromFile(ROOT . DS . 'data' . DS . 'model.dat');

            $prediction = $estimator->predictIt($data);
            $probabilities = $estimator->predictProb($data);
        }


        $this->set(compact('options', 'prediction'));

        $this->set(compact('options', 'prediction', 'probabilities'));
        $this->set('_serialize', ['prediction', 'probabilities']);
    }

}
