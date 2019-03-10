<?php $this->loadHelper('Time'); ?>
<div class="__dashboard-item box box-primary">
    <div class="box-header with-border">
        <span class="box-title"><?= __d('shop', 'Top selling products'); ?></span>
    </div>
    <div class="box-body">
        <?= $this->cell('Shop.ReportTopSeller::products', [], ['age' => 365]); ?>
    </div>
    <div class="box-footer">
        <?= $this->Button->create(__d('shop', 'List all products'), [
            'url' => ['plugin' => 'Shop', 'controller' => 'ShopProducts', 'action' => 'index'],
            'icon' => 'list'
        ]); ?>
    </div>
</div>