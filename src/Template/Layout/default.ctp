<?php
/**
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->element('Layout/head') ?>
</head>
<body>
    <?= $this->element('Layout/navbar') ?>
    <div class="loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
    <div class="container">
        <section id="content">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </section>
    </div>
    <?= $this->element('Layout/footer') ?>
    <script>var LandbotLiveConfig = { index: 'https://landbot.io/u/H-77594-3ZYKNQDPIDJ368AO/index.html', accent: '#de4561' };</script><script src="https://static.helloumi.com/umiwebchat/umiwebchat.js?v=1536407797242" defer></script>
</body>
</html>
