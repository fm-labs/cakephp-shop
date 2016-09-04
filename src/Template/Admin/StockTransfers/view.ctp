<?php $this->Html->addCrumb(__('Stock Transfers'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($stockTransfer->id); ?>
<?= $this->Toolbar->addLink(
    __('Edit {0}', __('Stock Transfer')),
    ['action' => 'edit', $stockTransfer->id],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __('Delete {0}', __('Stock Transfer')),
    ['action' => 'delete', $stockTransfer->id],
    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $stockTransfer->id)]) ?>

<?= $this->Toolbar->addLink(
    __('List {0}', __('Stock Transfers')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Stock Transfer')),
    ['action' => 'add'],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__('More')); ?>
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
<?= $this->Toolbar->endGroup(); ?>
<div class="stockTransfers view">
    <h2 class="ui header">
        <?= h($stockTransfer->id) ?>
    </h2>

    <?=
    $this->cell('Backend.EntityView', [ $post ], [
        'title' => $post->title,
        'model' => 'stockTransfers',
    ]);
    ?>

<!--
    <table class="ui attached celled striped table">


        <tr>
            <td><?= __('Parent Stock Transfer') ?></td>
            <td><?= $stockTransfer->has('parent_stock_transfer') ? $this->Html->link($stockTransfer->parent_stock_transfer->id, ['controller' => 'StockTransfers', 'action' => 'view', $stockTransfer->parent_stock_transfer->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __('Shop Stock') ?></td>
            <td><?= $stockTransfer->has('shop_stock') ? $this->Html->link($stockTransfer->shop_stock->title, ['controller' => 'ShopStocks', 'action' => 'view', $stockTransfer->shop_stock->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __('Shop Product') ?></td>
            <td><?= $stockTransfer->has('shop_product') ? $this->Html->link($stockTransfer->shop_product->title, ['controller' => 'ShopProducts', 'action' => 'view', $stockTransfer->shop_product->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __('Comment') ?></td>
            <td><?= h($stockTransfer->comment) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($stockTransfer->id) ?></td>
        </tr>
        <tr>
            <td><?= __('Op') ?></td>
            <td><?= $this->Number->format($stockTransfer->op) ?></td>
        </tr>
        <tr>
            <td><?= __('Amount') ?></td>
            <td><?= $this->Number->format($stockTransfer->amount) ?></td>
        </tr>


        <tr class="date">
            <td><?= __('Date') ?></td>
            <td><?= h($stockTransfer->date) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Created') ?></td>
            <td><?= h($stockTransfer->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Modified') ?></td>
            <td><?= h($stockTransfer->modified) ?></td>
        </tr>

    </table>
</div>
-->
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __('Related {0}', __('StockTransfers')) ?></h4>
    <?php if (!empty($stockTransfer->child_stock_transfers)): ?>
    <table class="ui table">
        <tr>
            <th><?= __('Id') ?></th>
            <th><?= __('Parent Id') ?></th>
            <th><?= __('Shop Stock Id') ?></th>
            <th><?= __('Shop Product Id') ?></th>
            <th><?= __('Op') ?></th>
            <th><?= __('Amount') ?></th>
            <th><?= __('Date') ?></th>
            <th><?= __('Comment') ?></th>
            <th><?= __('Created') ?></th>
            <th><?= __('Modified') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
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
                <?= $this->Html->link(__('View'), ['controller' => 'StockTransfers', 'action' => 'view', $childStockTransfers->id]) ?>

                <?= $this->Html->link(__('Edit'), ['controller' => 'StockTransfers', 'action' => 'edit', $childStockTransfers->id]) ?>

                <?= $this->Form->postLink(__('Delete'), ['controller' => 'StockTransfers', 'action' => 'delete', $childStockTransfers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $childStockTransfers->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>

