<div class="dashboard-item">
    <h3><?= __d('shop', 'Latest orders'); ?></h3>
    <?= $this->cell('Shop.LatestOrders'); ?>

    <?= $this->Html->link(__d('shop', 'All orders'), ['plugin' => 'Shop', 'controller' => 'ShopOrders', 'action' => 'index']); ?>
</div>