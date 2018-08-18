<?php $this->Breadcrumbs->add(__d('shop','Stock Transfers'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Edit {0}', __d('shop','Stock Transfer'))); ?>
<?php $this->Toolbar->addPostLink(
    __d('shop','Delete'),
    ['action' => 'delete', $stockTransfer->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $stockTransfer->id)]
)
?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Stock Transfers')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Parent Stock Transfers')),
    ['controller' => 'StockTransfers', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Parent Stock Transfer')),
    ['controller' => 'StockTransfers', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Stocks')),
    ['controller' => 'ShopStocks', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Stock')),
    ['controller' => 'ShopStocks', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop','Edit {0}', __d('shop','Stock Transfer')) ?>
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

    <?= $this->Form->button(__d('shop','Submit')) ?>
    <?= $this->Form->end() ?>

</div>