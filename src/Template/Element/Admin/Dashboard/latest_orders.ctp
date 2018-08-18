<div class="__dashboard-item box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?= __d('shop', 'Latest orders'); ?></h3>
    </div>
    <div class="box-body">
        <?= $this->cell('Shop.LatestOrders'); ?>
    </div>
    <div class="box-footer">
        <?= $this->Button->create(__d('shop', 'All orders'), [
            'url' => ['plugin' => 'Shop', 'controller' => 'ShopOrders', 'action' => 'index'],
            'icon' => 'list'
        ]); ?>
    </div>
</div>