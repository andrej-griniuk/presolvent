# Presolvent

Uses [CakePHP](https://cakephp.org/) framework and [PHP-ML](https://php-ml.readthedocs.io) library for machine learning.

It also exposes `/predict.json` API endpoint that can by used by Chat Bots or other 3rd party services (e.g. `/predict.json?state=New South Wales&region=Greater Sydney&suburb=Auburn&sex=Male&family-situation=Single without Dependants&occupation=Arts and Media Professionals&income-source=Self Employment&income=$50000-$99999&debts=$0-$49999&assets=$50000-$99999`).

## Setup

1. Run `composer install`
2. Place the dataset to `data/dataset.csv` and train the ML model by running `bin/cake train`
