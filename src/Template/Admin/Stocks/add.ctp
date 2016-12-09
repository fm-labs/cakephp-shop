<?php $this->Breadcrumbs->add(__('Stocks'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('New {0}', __('Stock'))); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Stocks')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Shop Stock Transfers')),
    ['controller' => 'ShopStockTransfers', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __('New {0}', __('Shop Stock Transfer')),
    ['controller' => 'ShopStockTransfers', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Shop Stock Values')),
    ['controller' => 'ShopStockValues', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __('New {0}', __('Shop Stock Value')),
    ['controller' => 'ShopStockValues', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Add {0}', __('Stock')) ?>
    </h2>
    <?= $this->Form->create($stock, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                echo $this->Form->input('title');
                echo $this->Form->input('is_default');
        ?>
        </div>

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

</div>