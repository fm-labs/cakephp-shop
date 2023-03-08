<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::getLocale(); ?>">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->fetch('meta') ?>
    <title>
        <?= $this->fetch('title') ?>
    </title>

    <?= $this->fetch('css') ?>
    <?= $this->fetch('headjs') ?>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top" id="nav-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="/shop">
                <span><?= __d('shop','Shop'); ?></span>
            </a>
        </div>
    </div>
</nav>
<div id="page">
    <div id="container">
        <main id="content" role="main">
            <div class="container">
                <?= $this->Flash->render() ?>
                <h1><?= $this->fetch('heading'); ?></h1>
                <?= $this->fetch('content') ?>
            </div>
        </main>
    </div>
</div>
<?= $this->fetch('scriptBottom'); ?>
<?= $this->fetch('script'); ?>
</body>
</html>
