<?php
/**
 * @var \App\View\AppView $this
 * @var array $options
 * @var string $prediction
 * @var array $probabilities
 */

$sexes = [
    "Female",
    "Male",
    "Unknown"
];

$familySituations = [
    "Couple with Dependants",
    "Couple without Dependants",
    "Single with Dependants",
    "Single without Dependants",
    "Unknown",
];

$occupations = [
    "",
    "AFSA",
    "Arts and Media Professionals",
    "Automotive and Engineering Trades Workers",
    "Business, Human Resource and Marketing Professionals",
    "Carers and Aides",
    "Chief Executives, General Managers and Legislators",
    "Cleaners and Laundry Workers",
    "Clerical and Office Support Workers",
    "Construction Trades Workers",
    "Construction and Mining Labourers",
    "Design, Engineering, Science and Transport Professionals",
    "Education Professionals",
    "Electrotechnology and Telecommunications Trades Workers",
    "Engineering, ICT and Science Technicians",
    "Factory Process Workers",
    "Farm, Forestry and Garden Workers",
    "Farmers and Farm Managers",
    "Food Preparation Assistants",
    "Food Trades Workers",
    "General Clerical Workers",
    "Health Professionals",
    "Health and Welfare Support Workers",
    "Hospitality Workers",
    "Hospitality, Retail and Service Managers",
    "ICT Professionals",
    "Inquiry Clerks and Receptionists",
    "Legal, Social and Welfare Professionals",
    "Machine and Stationary Plant Operators",
    "Mobile Plant Operators",
    "Numerical Clerks",
    "Office Managers and Program Administrators",
    "Other Clerical and Administrative Workers",
    "Other Labourers",
    "Other Technicians and Trades Workers",
    "Personal Assistants and Secretaries",
    "Protective Service Workers",
    "Road and Rail Drivers",
    "Sales Assistants and Salespersons",
    "Sales Representatives and Agents",
    "Sales Support Workers",
    "Skilled Animal and Horticultural Workers",
    "Specialist Managers",
    "Sports and Personal Service Workers",
    "Storepersons"
];

$incomes = [
    "$0-$49999",
    "$-100000-$-50001",
    "$-50000-$-1",
    "$100000-$149999",
    "$150000-$199999",
    "$50000-$99999",
    "More Than $200000",
];

$incomeSources = [
    "Other",
    "Unknown",
    "Business earnings",
    "Deceased Estate or Trusts",
    "Government benefits\/Pensions",
    "Gross Wages and Salary",
    "Income from Investments",
    "Income from reverse mortgage",
    "Lump Sum termination payments",
    "Self Employment",
    "Superannuation"
];

$depts = [
    "$0-$49999",
    "$50000-$99999",
    "$100000-$149999",
    "$150000-$199999",
    "$200000-$249999",
    "$250000-$299999",
    "$300000-$349999",
    "$350000-$399999",
    "$400000-$449999",
    "$450000-$499999",
    "$500000-$549999",
    "$550000-$599999",
    "$600000-$649999",
    "$650000-$699999",
    "$700000-$749999",
    "$750000-$799999",
    "$800000-$849999",
    "$850000-$899999",
    "$900000-$949999",
    "$950000-$999999",
    "More Than $1000000"
];

$assets = [
    "$0-$49999",
    "$50000-$99999",
    "$100000-$149999",
    "$150000-$199999",
    "$200000-$249999",
    "$250000-$299999",
    "$300000-$349999",
    "$350000-$399999",
    "$400000-$449999",
    "$450000-$499999",
    "$500000-$549999",
    "$550000-$599999",
    "$600000-$649999",
    "$650000-$699999",
    "$700000-$749999",
    "$750000-$799999",
    "$800000-$849999",
    "$850000-$899999",
    "$900000-$949999",
    "$950000-$999999",
    "More Than $1000000"
];

$states = $options['states'];
?>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <form class="pure-steps" method="post" action="<?= $this->Url->build() ?>" id="form">
                <input type="radio" name="steps" class="pure-steps_radio" id="step-0" checked="">
                <input type="radio" name="steps" class="pure-steps_radio" id="step-1">
                <input type="radio" name="steps" class="pure-steps_radio" id="step-2">
                <div class="pure-steps_group">
                    <ol>
                        <li class="pure-steps_group-step">
                            <header>
                                <h2 class="pure-steps_group-step_legend">Welcome to <strong style="font-weight: bold">Presolvent</strong></h2>
                                <p class="pure-steps_group-step_item">Presolvent uses <strong>data</strong> from the <strong>Australian Financial Security Authority</strong>, in combination with <strong>ATO</strong> data to help <strong>predict when non-compliance</strong> might occur.</p>
                                <p class="pure-steps_group-step_item">High-risk individuals <strong>identified</strong> by Presolvent can be given additional <strong>support and guidance</strong> about managing their finances, agreeing only to appropriate debt and insolvency agreements, so they can better meet their obligations.</p>
                                <p class="pure-steps_group-step_item">Presolvent uses <strong>Machine Learning</strong> which is <strong>trained</strong> on limited datasets provided by the relevant Australian government bodies, it makes predictions of insolvency risk, but Presolvent is only a tool to be used in conjunction with human-assessed insolvency risk factors.</p>
                                <p class="pure-steps_group-step_item"></p>
                            </header>
                        </li>
                        <li class="pure-steps_group-step" style="padding:2em">

                            <div class="row">
                                <div class="col-xs-4">
                                    <?= $this->Form->control('state', ['label' => __('State'), 'id' => 'state', 'options' => array_combine(array_keys($states), array_keys($states))]) ?>
                                </div>
                                <div class="col-xs-4">
                                    <?= $this->Form->control('region', ['label' => __('Region'), 'id' => 'gccsa', 'options' => []]) ?>
                                </div>
                                <div class="col-xs-4">
                                    <?= $this->Form->control('suburb', ['label' => __('Suburb'), 'id' => 'sa', 'options' => []]) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6"><?= $this->Form->control('sex', ['label' => __('Sex'), 'options' => array_combine($sexes, $sexes)]) ?></div>
                                <div class="col-xs-6"><?= $this->Form->control('family-situation', ['label' => __('Family Situation'), 'options' => array_combine($familySituations, $familySituations) ]) ?></div>
                            </div>
                            <?= $this->Form->control('occupation', ['label' => __('Occupation'), 'options' => array_combine($occupations, $occupations)]) ?>
                            <?= $this->Form->control('income-source', ['label' => __('Primary Income Source'), 'options' => array_combine($incomeSources, $incomeSources)]) ?>
                            <?= $this->Form->control('income', ['label' => __('Income'), 'options' => array_combine($incomes, $incomes)]) ?>
                            <?= $this->Form->control('debts', ['label' => __('Unsecured Debts'), 'options' => array_combine($depts, $depts)]) ?>
                            <?= $this->Form->control('assets', ['label' => __('Value of Assets'), 'options' => array_combine($assets, $assets)]) ?>

                        </li>
                        <li class="pure-steps_group-step flexy-item" style="padding:2em" id="result">
                            <div class="row" style="width:100%">
                                <div class="col-xs-12">
                                </div>
                                <div class="col-xs-12">
                                    <div class="pure-steps_preload">
                                        <i class="fa fa-check"></i>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ol>
                    <ol class="pure-steps_group-triggers">
                        <li class="pure-steps_group-triggers_item">
                            <label for="step-0">Restart</label>
                        </li>
                        <li class="pure-steps_group-triggers_item">
                            <label for="step-1"><?= __('Start risk assessment') ?></label>
                        </li>
                        <li class="pure-steps_group-triggers_item">
                            <label for="step-2" id="submit"><?= __('Predict insolvency risk') ?></label>
                        </li>
                    </ol>
                </div>
                <br>
                <div class="text-center">
                    <label for="step-0" style="text-align: center; font-weight: normal">Restart</label>
                </div>
            </form>
        </div>
    </div>

<?php $this->append('script') ?>
    <script>
        var states = <?= json_encode($states) ?>;
    </script>
<?php $this->end() ?>
