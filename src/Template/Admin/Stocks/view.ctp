<?php $this->Html->addCrumb(__('Stocks'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($stock->title); ?>
<?= $this->Toolbar->addLink(
    __('Edit {0}', __('Stock')),
    ['action' => 'edit', $stock->id],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __('Delete {0}', __('Stock')),
    ['action' => 'delete', $stock->id],
    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $stock->id)]) ?>

<?= $this->Toolbar->addLink(
    __('List {0}', __('Stocks')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Stock')),
    ['action' => 'add'],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__('More')); ?>
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
<?= $this->Toolbar->endGroup(); ?>
<div class="stocks view">
    <h2 class="ui header">
        <?= h($stock->title) ?>
    </h2>

    <?=
    $this->cell('Backend.EntityView', [ $post ], [
        'title' => $post->title,
        'model' => 'stocks',
    ]);
    ?>

<!--
    <table class="ui attached celled striped table">


        <tr>
            <td><?= __('Title') ?></td>
            <td><?= h($stock->title) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($stock->id) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __('Is Default') ?></td>
            <td><?= $stock->is_default ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
-->
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __('Related {0}', __('ShopStockTransfers')) ?></h4>
    <?php if (!empty($stock->shop_stock_transfers)): ?>
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
        <?php foreach ($stock->shop_stock_transfers as $shopStockTransfers): ?>
        <tr>
            <td><?= h($shopStockTransfers->id) ?></td>
            <td><?= h($shopStockTransfers->parent_id) ?></td>
            <td><?= h($shopStockTransfers->shop_stock_id) ?></td>
            <td><?= h($shopStockTransfers->shop_product_id) ?></td>
            <td><?= h($shopStockTransfers->op) ?></td>
            <td><?= h($shopStockTransfers->amount) ?></td>
            <td><?= h($shopStockTransfers->date) ?></td>
            <td><?= h($shopStockTransfers->comment) ?></td>
            <td><?= h($shopStockTransfers->created) ?></td>
            <td><?= h($shopStockTransfers->modified) ?></td>

            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'ShopStockTransfers', 'action' => 'view', $shopStockTransfers->id]) ?>

                <?= $this->Html->link(__('Edit'), ['controller' => 'ShopStockTransfers', 'action' => 'edit', $shopStockTransfers->id]) ?>

                <?= $this->Form->postLink(__('Delete'), ['controller' => 'ShopStockTransfers', 'action' => 'delete', $shopStockTransfers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $shopStockTransfers->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __('Related {0}', __('ShopStockValues')) ?></h4>
    <?php if (!empty($stock->shop_stock_values)): ?>
    <table class="ui table">
        <tr>
            <th><?= __('Id') ?></th>
            <th><?= __('Shop Stock Id') ?></th>
            <th><?= __('Shop Product Id') ?></th>
            <th><?= __('Value') ?></th>
            <th><?= __('Last Transfer In') ?></th>
            <th><?= __('Last Transfer Out') ?></th>
            <th><?= __('Created') ?></th>
            <th><?= __('Modified') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($stock->shop_stock_values as $shopStockValues): ?>
        <tr>
            <td><?= h($shopStockValues->id) ?></td>
            <td><?= h($shopStockValues->shop_stock_id) ?></td>
            <td><?= h($shopStockValues->shop_product_id) ?></td>
            <td><?= h($shopStockValues->value) ?></td>
            <td><?= h($shopStockValues->last_transfer_in) ?></td>
            <td><?= h($shopStockValues->last_transfer_out) ?></td>
            <td><?= h($shopStockValues->created) ?></td>
            <td><?= h($shopStockValues->modified) ?></td>

            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'ShopStockValues', 'action' => 'view', $shopStockValues->id]) ?>

                <?= $this->Html->link(__('Edit'), ['controller' => 'ShopStockValues', 'action' => 'edit', $shopStockValues->id]) ?>

                <?= $this->Form->postLink(__('Delete'), ['controller' => 'ShopStockValues', 'action' => 'delete', $shopStockValues->id], ['confirm' => __('Are you sure you want to delete # {0}?', $shopStockValues->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>

