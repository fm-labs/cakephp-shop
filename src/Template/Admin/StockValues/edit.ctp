<?php $this->Html->addCrumb(__('Stock Values'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('Edit {0}', __('Stock Value'))); ?>
<?= $this->Toolbar->addPostLink(
    __('Delete'),
    ['action' => 'delete', $stockValue->id],
    ['data-icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $stockValue->id)]
)
?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Stock Values')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Shop Stocks')),
    ['controller' => 'ShopStocks', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __('New {0}', __('Shop Stock')),
    ['controller' => 'ShopStocks', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __('New {0}', __('Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Edit {0}', __('Stock Value')) ?>
    </h2>
    <?= $this->Form->create($stockValue, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                    echo $this->Form->input('shop_stock_id', ['options' => $shopStocks]);
                    echo $this->Form->input('shop_product_id', ['options' => $shopProducts]);
                echo $this->Form->input('value');
                //echo $this->Form->input('last_transfer_in');
                //echo $this->Form->input('last_transfer_out');
        ?>
        </div>

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

</div>