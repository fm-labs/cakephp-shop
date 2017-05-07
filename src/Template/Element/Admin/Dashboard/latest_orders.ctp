<div class="dashboard-item">
    <h3><?= __('Latest orders'); ?></h3>
    <?= $this->cell('Shop.LatestOrders'); ?>

    <?= $this->Html->link(__('All orders'), ['plugin' => 'Shop', 'controller' => 'ShopOrders', 'action' => 'index']); ?>
</div>