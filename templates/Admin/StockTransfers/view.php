<?php $this->Breadcrumbs->add(__d('shop','Stock Transfers'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($stockTransfer->id); ?>
<?php $this->Toolbar->addLink(
    __d('shop','Edit {0}', __d('shop','Stock Transfer')),
    ['action' => 'edit', $stockTransfer->id],
    ['data-icon' => 'edit']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','Delete {0}', __d('shop','Stock Transfer')),
    ['action' => 'delete', $stockTransfer->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $stockTransfer->id)]) ?>

<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Stock Transfers')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Stock Transfer')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->startGroup(__d('shop','More')); ?>
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
<div class="stockTransfers view">
    <h2 class="ui header">
        <?= h($stockTransfer->id) ?>
    </h2>

    <?=
    $this->cell('Admin.EntityView', [ $post ], [
        'title' => $post->title,
        'model' => 'stockTransfers',
    ]);
    ?>

<!--
    <table class="ui attached celled striped table">


        <tr>
            <td><?= __d('shop','Parent Stock Transfer') ?></td>
            <td><?= $stockTransfer->has('parent_stock_transfer') ? $this->Html->link($stockTransfer->parent_stock_transfer->id, ['controller' => 'StockTransfers', 'action' => 'view', $stockTransfer->parent_stock_transfer->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shop Stock') ?></td>
            <td><?= $stockTransfer->has('shop_stock') ? $this->Html->link($stockTransfer->shop_stock->title, ['controller' => 'ShopStocks', 'action' => 'view', $stockTransfer->shop_stock->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shop Product') ?></td>
            <td><?= $stockTransfer->has('shop_product') ? $this->Html->link($stockTransfer->shop_product->title, ['controller' => 'ShopProducts', 'action' => 'view', $stockTransfer->shop_product->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Comment') ?></td>
            <td><?= h($stockTransfer->comment) ?></td>
        </tr>


        <tr>
            <td><?= __d('shop','Id') ?></td>
            <td><?= $this->Number->format($stockTransfer->id) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Op') ?></td>
            <td><?= $this->Number->format($stockTransfer->op) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Amount') ?></td>
            <td><?= $this->Number->format($stockTransfer->amount) ?></td>
        </tr>


        <tr class="date">
            <td><?= __d('shop','Date') ?></td>
            <td><?= h($stockTransfer->date) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Created') ?></td>
            <td><?= h($stockTransfer->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Modified') ?></td>
            <td><?= h($stockTransfer->modified) ?></td>
        </tr>

    </table>
</div>
-->
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __d('shop','Related {0}', __d('shop','StockTransfers')) ?></h4>
    <?php if (!empty($stockTransfer->child_stock_transfers)): ?>
    <table class="ui table">
        <tr>
            <th><?= __d('shop','Id') ?></th>
            <th><?= __d('shop','Parent Id') ?></th>
            <th><?= __d('shop','Shop Stock Id') ?></th>
            <th><?= __d('shop','Shop Product Id') ?></th>
            <th><?= __d('shop','Op') ?></th>
            <th><?= __d('shop','Amount') ?></th>
            <th><?= __d('shop','Date') ?></th>
            <th><?= __d('shop','Comment') ?></th>
            <th><?= __d('shop','Created') ?></th>
            <th><?= __d('shop','Modified') ?></th>
            <th class="actions"><?= __d('shop','Actions') ?></th>
        </tr>
        <?php foreach ($stockTransfer->child_stock_transfers as $childStockTransfers): ?>
        <tr>
            <td><?= h($childStockTransfers->id) ?></td>
            <td><?= h($childStockTransfers->parent_id) ?></td>
            <td><?= h($childStockTransfers->shop_stock_id) ?></td>
            <td><?= h($childStockTransfers->shop_product_id) ?></td>
            <td><?= h($childStockTransfers->op) ?></td>
            <td><?= h($childStockTransfers->amount) ?></td>
            <td><?= h($childStockTransfers->date) ?></td>
            <td><?= h($childStockTransfers->comment) ?></td>
            <td><?= h($childStockTransfers->created) ?></td>
            <td><?= h($childStockTransfers->modified) ?></td>

            <td class="actions">
                <?= $this->Html->link(__d('shop','View'), ['controller' => 'StockTransfers', 'action' => 'view', $childStockTransfers->id]) ?>

                <?= $this->Html->link(__d('shop','Edit'), ['controller' => 'StockTransfers', 'action' => 'edit', $childStockTransfers->id]) ?>

                <?= $this->Form->postLink(__d('shop','Delete'), ['controller' => 'StockTransfers', 'action' => 'delete', $childStockTransfers->id], ['confirm' => __d('shop','Are you sure you want to delete # {0}?', $childStockTransfers->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>

