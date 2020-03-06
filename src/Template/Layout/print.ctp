<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::getLocale(); ?>">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>
</head>
<body class="print" style="width: 297mm; margin: 0 auto;">
    <!--
    [Print view]
    <div class="print-panel">
        <a href="javascript:print()">Drucken</a>
    </div>
    -->

    <div class="print-wrapper">
        <?= $this->fetch('content'); ?>
    </div>
</body>
</html>
