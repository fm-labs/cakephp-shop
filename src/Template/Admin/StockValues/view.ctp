<?php $this->Html->addCrumb(__('Stock Values'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($stockValue->id); ?>
<?= $this->Toolbar->addLink(
    __('Edit {0}', __('Stock Value')),
    ['action' => 'edit', $stockValue->id],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __('Delete {0}', __('Stock Value')),
    ['action' => 'delete', $stockValue->id],
    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $stockValue->id)]) ?>

<?= $this->Toolbar->addLink(
    __('List {0}', __('Stock Values')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Stock Value')),
    ['action' => 'add'],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__('More')); ?>
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
<?= $this->Toolbar->endGroup(); ?>
<div class="stockValues view">
    <h2 class="ui header">
        <?= h($stockValue->id) ?>
    </h2>

    <?=
    $this->cell('Backend.EntityView', [ $post ], [
        'title' => $post->title,
        'model' => 'stockValues',
    ]);
    ?>

<!--
    <table class="ui attached celled striped table">


        <tr>
            <td><?= __('Shop Stock') ?></td>
            <td><?= $stockValue->has('shop_stock') ? $this->Html->link($stockValue->shop_stock->title, ['controller' => 'ShopStocks', 'action' => 'view', $stockValue->shop_stock->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __('Shop Product') ?></td>
            <td><?= $stockValue->has('shop_product') ? $this->Html->link($stockValue->shop_product->title, ['controller' => 'ShopProducts', 'action' => 'view', $stockValue->shop_product->id]) : '' ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($stockValue->id) ?></td>
        </tr>
        <tr>
            <td><?= __('Value') ?></td>
            <td><?= $this->Number->format($stockValue->value) ?></td>
        </tr>


        <tr class="date">
            <td><?= __('Last Transfer In') ?></td>
            <td><?= h($stockValue->last_transfer_in) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Last Transfer Out') ?></td>
            <td><?= h($stockValue->last_transfer_out) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Created') ?></td>
            <td><?= h($stockValue->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Modified') ?></td>
            <td><?= h($stockValue->modified) ?></td>
        </tr>

    </table>
</div>
-->

