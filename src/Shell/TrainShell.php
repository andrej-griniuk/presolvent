<?php
namespace App\Shell;

use App\Phpml\Classification\NaiveBayes;
use Cake\Console\ConsoleIo;
use Cake\Console\Shell;
use Cake\ORM\Locator\LocatorInterface;
use Cake\Utility\Text;
use Phpml\Dataset\CsvDataset;
use Phpml\ModelManager;

/**
 * Train shell command.
 */
class TrainShell extends Shell
{

    public const FEATURES = [
        // 0 => 'Unique ID',
        // 1 => 'Calendar Year of Insolvency',
        2 => 'SA3 of Debtor',           // 0
        // 3 => 'SA3 Code of Debtor',
        4 => 'GCCSA of Debtor',         // 1
        // 5 => 'GCCSA Code of Debtor',
        6 => 'State of Debtor',         // 2
        7 => 'Sex of Debtor',           // 3
        8 => 'Family Situation',        // 4
        // 9 => 'Debtor Occupation Code (ANZSCO)',
        10 => 'Debtor Occupation Name (ANZSCO)',    // 5
        // 11 => 'Main Cause of Insolvency',
        // 12 => 'Business Related Insolvency',
        13 => 'Debtor Income',          // 6
        14 => 'Primary Income Source',  // 7
        15 => 'Unsecured Debts',        // 8
        16 => 'Value of Assets',        // 9
    ];

    public const TARGETS = [
        17 => 'Type of Party',
        18 => 'Non-Compliance Type',
        19 => 'Result of Non-Compliance',
        20 => 'Number of Instances',
        21 => 'Outcome of Non-Compliance',
        22 => 'Non-Compliance Conviction Result',
    ];

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

    public function main()
    {
        $this->run();
        $this->train();
    }

    public function run($filename = 'dataset.csv', $limit = 0)
    {
        $this->out('Converting dataset...');

        $cols = static::FEATURES + static::TARGETS;
        $options = [];
        $states = [];

        $input = fopen(ROOT . DS . 'data' . DS . $filename, 'r');
        $output = fopen(ROOT . DS . 'data' . DS . 'converted-' . $filename, 'w');

        $i = 0;
        $nonCompliant = 0;
        while (($data = fgetcsv($input)) !== false) {
            if ($i != 0) {
                // Save unique features and targets
                foreach ($cols as $k => $v) {
                    $value = $data[$k];
                    if ($value == 'Not Stated') {
                        $value = 'Unknown';
                    }
                    if (!isset($options[$v])) {
                        $options[$v] = [];
                    }

                    if (!in_array($value, $options[$v])) {
                        $options[$v][] = $value;
                    }
                }

                if (!isset($states[$data[6]][$data[4]])) {
                    $states[$data[6]][$data[4]] = [];
                }
                if (!in_array($data[2], $states[$data[6]][$data[4]])) {
                    $states[$data[6]][$data[4]][] = $data[2];
                }
            }

            // Output
            $row = [];
            foreach (static::FEATURES as $k => $v) {
                $value = $data[$k];
                if ($value == 'Not Stated') {
                    $value = 'Unknown';
                }
                $row[] = $value;
            }
            // Target field
            if ($row[] = $data[18]) {
                $nonCompliant++;
            }
            fputcsv($output, $row);

            $i++;
            if ($limit && $i >= $limit) {
                break;
            }
        }
        fclose($input);
        fclose($output);

        $this->info('Non-compliant: ' . $nonCompliant);

        ksort($states);
        foreach ($states as $k => &$v) {
            ksort($v);
            foreach ($v as &$suburbs) {
                sort($suburbs);
            }
            unset($suburbs);
            unset($v);
        }

        $opts = [];
        foreach ($options as $k => $v) {
            sort($v);
            $opts[Text::slug(strtolower($k))] = $v;
        }
        $opts['options'] = $opts;
        $opts['states'] = $states;

        file_put_contents(ROOT . DS . 'data' . DS . 'options.json', json_encode($opts));

        $this->success('Complete!');
    }

    public function train($filename = 'converted-dataset.csv', $model = 'model.dat')
    {
        $this->out('Training...');

        $dataset = new CsvDataset(ROOT . DS . 'data' . DS . $filename, count(static::FEATURES), true);

        $estimator = new NaiveBayes();
        $estimator->train($dataset->getSamples(), $dataset->getTargets());

        $modelManager = new ModelManager();
        $modelManager->saveToFile($estimator, ROOT . DS . 'data' . DS . $model);

        $this->success('New model trained!');
    }

    public function test($filename = 'converted-dataset.csv', $model = 'model.dat')
    {
        $modelManager = new ModelManager();
        /** @var \App\Phpml\Classification\NaiveBayes $estimator */
        $estimator = $modelManager->restoreFromFile(ROOT . DS . 'data' . DS . $model);

        $input = fopen(ROOT . DS . 'data' . DS . $filename, 'r');

        $compliantCount = 0;
        $predictedCount = 0;
        $matchesCount = 0;
        $totalMatchCount = 0;
        fgetcsv($input);
        while (($data = fgetcsv($input)) !== false) {
            if ($compliant = array_pop($data)) {
                $compliantCount++;
            }


            if ($predicted = $estimator->predictIt($data)) {
                $predictedCount++;
            }

            if ($compliant && $predicted) {
                $matchesCount++;

                if ($compliant == $predicted) {
                    $totalMatchCount++;
                }
            }
        }

        fclose($input);

        $this->out('Non-Compliant count: ' . $compliantCount);
        $this->out('Predicted count: ' . $predictedCount);
        $this->out('Matches count: ' . $matchesCount);
        $this->out('Total matches count: ' . $totalMatchCount);
    }

















/*
    public function main2()
    {
        ini_set('memory_limit', '1024M');
        //$this->out($this->OptionParser->help());


        $csv = new CsvDataset(ROOT . DS . 'data' . DS . 'dataset-converted.csv', 13, true, ',');
        $dataset = new RandomSplit($csv, 0.3);
        $estimator = new SVR(Kernel::LINEAR);
        $estimator->train($dataset->getTrainSamples(), $dataset->getTrainLabels());

        $this->out(sprintf('R2: %s', pow(Correlation::pearson(
            $dataset->getTestLabels(),
            $estimator->predict($dataset->getTestSamples())
        ), 2)));

        $modelManager = new ModelManager();
        $modelManager->saveToFile($estimator, ROOT . DS . 'data' . DS . 'model.dat');

        $this->out('New model trained! :rocket:');
    }

    public function convert2()
    {
        $limit = 1000;

        $inputs = [
            // 0 => 'Unique ID',
            1 => 'Calendar Year of Insolvency',
            2 => 'SA3 of Debtor',
            // 3 => 'SA3 Code of Debtor',
            4 => 'GCCSA of Debtor',
            // 5 => 'GCCSA Code of Debtor',
            6 => 'State of Debtor',
            7 => 'Sex of Debtor',
            8 => 'Family Situation',
            // 9 => 'Debtor Occupation Code (ANZSCO)',
            10 => 'Debtor Occupation Name (ANZSCO)',
            11 => 'Main Cause of Insolvency',
            12 => 'Business Related Insolvency',
            13 => 'Debtor Income',
            14 => 'Primary Income Source',
            15 => 'Unsecured Debts',
            16 => 'Value of Assets',
        ];

        $outputs = [
            17 => 'Type of Party',
            18 => 'Non-Compliance Type',
            19 => 'Result of Non-Compliance',
            20 => 'Number of Instances',
            21 => 'Outcome of Non-Compliance',
            22 => 'Non-Compliance Conviction Result',
        ];

        $cols = $inputs + $outputs;
        $options = [];

        $f = fopen(ROOT . DS . 'data' . DS . 'dataset.csv', 'r');

        fgetcsv($f, 1000, ',');
        $i = 0;
        while (($data = fgetcsv($f, 1000, ',')) !== false) {
            foreach ($cols as $k => $v) {
                if (!isset($options[$v])) {
                    $options[$v] = [];
                }

                if (!in_array($data[$k], $options[$v])) {
                    $options[$v][] = $data[$k];
                }
            }

            if ($i++ >= $limit) {
                break;
            }
        }

        fclose($f);

        foreach ($options as $col => &$opts) {
            sort($opts);
            unset($opts);
        }
        //dd($options);

        $f = fopen(ROOT . DS . 'data' . DS . 'dataset.csv', 'r');
        $result = fopen(ROOT . DS . 'data' . DS . 'dataset-converted.csv', 'w');

        fgetcsv($f, 1000, ',');
        $i = 0;
        while (($data = fgetcsv($f, 1000, ',')) !== false) {
            $fields = [];
            foreach ($inputs as $k => $v) {
                //$fields[] = array_search($data[$k], $options[$v]);
                $fields[] = $data[$k];
            }

            //$fields[] = array_search($data[17], $options['Type of Party']);
            $fields[] = $data[17];

            fputcsv($result, $fields);

            if ($i++ >= $limit) {
                break;
            }
        }

        fclose($f);
        fclose($result);

        $opts = [];
        foreach ($options as $k => $v) {
            $opts[Text::slug(strtolower($k))] = $v;
        }

        file_put_contents(ROOT . DS . 'data' . DS . 'options.json', json_encode($opts));
    }

    public function train2()
    {
        $dataset = new CsvDataset(ROOT . DS . 'data' . DS . 'dataset-converted.csv', 13);

        $classifier = new NaiveBayes();
        $classifier->train($dataset->getSamples(), $dataset->getTargets());;

        foreach ($dataset->getSamples() as $sample) {
            //if (!$classifier->predict()) {
            //    continue;
            //}

            var_dump($classifier->predict($sample));
            var_dump($classifier->predictProb($sample));
        }

    }
*/
}
