<?php $this->Html->addCrumb(__('Stock Transfers'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('New {0}', __('Stock Transfer'))); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Stock Transfers')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Parent Stock Transfers')),
    ['controller' => 'StockTransfers', 'action' => 'index'],
    ['icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __('New {0}', __('Parent Stock Transfer')),
    ['controller' => 'StockTransfers', 'action' => 'add'],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Shop Stocks')),
    ['controller' => 'ShopStocks', 'action' => 'index'],
    ['icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __('New {0}', __('Shop Stock')),
    ['controller' => 'ShopStocks', 'action' => 'add'],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __('New {0}', __('Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Add {0}', __('Stock Transfer')) ?>
    </h2>
    <?= $this->Form->create($stockTransfer, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                    echo $this->Form->input('parent_id', ['options' => $parentStockTransfers, 'empty' => true]);
                    echo $this->Form->input('shop_stock_id', ['options' => $shopStocks]);
                    echo $this->Form->input('shop_product_id', ['options' => $shopProducts]);
                echo $this->Form->input('op');
                echo $this->Form->input('amount');
                //echo $this->Form->input('date');
                echo $this->Form->input('comment');
        ?>
        </div>

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

</div>