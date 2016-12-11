<?php $this->Breadcrumbs->add(__d('shop','Stock Values'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($stockValue->id); ?>
<?= $this->Toolbar->addLink(
    __d('shop','Edit {0}', __d('shop','Stock Value')),
    ['action' => 'edit', $stockValue->id],
    ['data-icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','Delete {0}', __d('shop','Stock Value')),
    ['action' => 'delete', $stockValue->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $stockValue->id)]) ?>

<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Stock Values')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Stock Value')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__d('shop','More')); ?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Stocks')),
    ['controller' => 'ShopStocks', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Stock')),
    ['controller' => 'ShopStocks', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['data-icon' => 'plus']
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
            <td><?= __d('shop','Shop Stock') ?></td>
            <td><?= $stockValue->has('shop_stock') ? $this->Html->link($stockValue->shop_stock->title, ['controller' => 'ShopStocks', 'action' => 'view', $stockValue->shop_stock->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shop Product') ?></td>
            <td><?= $stockValue->has('shop_product') ? $this->Html->link($stockValue->shop_product->title, ['controller' => 'ShopProducts', 'action' => 'view', $stockValue->shop_product->id]) : '' ?></td>
        </tr>


        <tr>
            <td><?= __d('shop','Id') ?></td>
            <td><?= $this->Number->format($stockValue->id) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Value') ?></td>
            <td><?= $this->Number->format($stockValue->value) ?></td>
        </tr>


        <tr class="date">
            <td><?= __d('shop','Last Transfer In') ?></td>
            <td><?= h($stockValue->last_transfer_in) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Last Transfer Out') ?></td>
            <td><?= h($stockValue->last_transfer_out) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Created') ?></td>
            <td><?= h($stockValue->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Modified') ?></td>
            <td><?= h($stockValue->modified) ?></td>
        </tr>

    </table>
</div>
-->

