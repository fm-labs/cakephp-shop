<?php $this->Html->addCrumb(__('Stocks'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('Stock'))); ?>
<?= $this->Toolbar->addPostLink(
    __('Delete'),
    ['action' => 'delete', $stock->id],
    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $stock->id)]
)
?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Stocks')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Shop Stock Transfers')),
    ['controller' => 'ShopStockTransfers', 'action' => 'index'],
    ['icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __('New {0}', __('Shop Stock Transfer')),
    ['controller' => 'ShopStockTransfers', 'action' => 'add'],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Shop Stock Values')),
    ['controller' => 'ShopStockValues', 'action' => 'index'],
    ['icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __('New {0}', __('Shop Stock Value')),
    ['controller' => 'ShopStockValues', 'action' => 'add'],
    ['icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Edit {0}', __('Stock')) ?>
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