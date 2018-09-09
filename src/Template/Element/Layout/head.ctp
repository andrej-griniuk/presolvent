<?php
/**
 * @var \App\View\AppView $this
 */

$this->Html->css([
    'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
    'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
    'https://fonts.googleapis.com/css?family=Open+Sans:700,300',
    'app'
], ['block' => true]);
?>
<?= $this->Html->charset() ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hackathon - <?= $this->fetch('title') ?></title>
<?= $this->Html->meta('icon') ?>
<?= $this->fetch('meta') ?>
<?= $this->fetch('css') ?>
