<div class="dashboard-item">
    <h3><?= __('Top selling products'); ?></h3>
    <?= $this->cell('Shop.ReportTopSeller::products'); ?>

    <?= ''//$this->Html->link(__('All reports'), ['plugin' => 'Shop', 'controller' => 'Reports', 'action' => 'index']); ?>
</div>