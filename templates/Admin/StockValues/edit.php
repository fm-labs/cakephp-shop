<?php $this->Breadcrumbs->add(__d('shop','Stock Values'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Edit {0}', __d('shop','Stock Value'))); ?>
<?php $this->Toolbar->addPostLink(
    __d('shop','Delete'),
    ['action' => 'delete', $stockValue->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $stockValue->id)]
)
?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Stock Values')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
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
        <?= __d('shop','Edit {0}', __d('shop','Stock Value')) ?>
    </h2>
    <?= $this->Form->create($stockValue, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                    echo $this->Form->control('shop_stock_id', ['options' => $shopStocks]);
                    echo $this->Form->control('shop_product_id', ['options' => $shopProducts]);
                echo $this->Form->control('value');
                //echo $this->Form->control('last_transfer_in');
                //echo $this->Form->control('last_transfer_out');
        ?>
        </div>

    <?= $this->Form->button(__d('shop','Submit')) ?>
    <?= $this->Form->end() ?>

</div>