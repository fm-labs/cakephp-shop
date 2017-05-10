<div class="dashboard-item">
    <h3><?= __d('shop', 'Top selling products'); ?></h3>
    <?= $this->cell('Shop.ReportTopSeller::products'); ?>

    <?= ''//$this->Html->link(__d('shop', 'All reports'), ['plugin' => 'Shop', 'controller' => 'Reports', 'action' => 'index']); ?>
</div>