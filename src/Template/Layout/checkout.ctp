<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::locale(); ?>">
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
    <div class="container" style="max-width: 900px;">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-top-navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="/shop">
                <?= $this->Html->image('/theme_lederleitner/img/frontend/Lederleitner_logo_1.png', ['height' => '30px']); ?>
                <span>Lederleitner Shop</span>
            </a>

            <div class="navbar-collapse collapse">

                <div id="header-user" class="navbar-right">
                    <span style="padding: 1em 5px; display: inline-block;">
                    <?php if ($this->request->session()->check('Auth.User.id')): ?>
                        <?= __d('shop', 'Hello, {0}', $this->request->session()->read('Auth.User.name')); ?>
                        <?= $this->Html->link(__d('shop', 'Logout'), ['_name' => 'user:logout'] ); ?>
                    <?php else: ?>
                        <?= $this->Html->link(__d('shop', 'Login'), ['_name' => 'user:login'] ); ?>
                    <?php endif; ?>
                    </span>
                </div>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</nav>
<div id="page">
    <div id="container">
        <header role="banner" class="container">
            <?= $this->section('before', [false]); ?>
        </header>

        <nav id="breadcrumbs" class="container">
            <?= $this->Breadcrumbs->render(['class' => 'breadcrumbs']); ?>
        </nav>

        <main id="content" role="main">
            <div class="container">
                <?= $this->Flash->render() ?>
                <h1><?= $this->fetch('heading'); ?></h1>
            </div>
            <?= $this->fetch('content', [false]) ?>
        </main>

        <footer class="container">
        </footer>
    </div>
</div>
<?= $this->fetch('scriptBottom'); ?>
<?= $this->fetch('script'); ?>
</body>
</html>
