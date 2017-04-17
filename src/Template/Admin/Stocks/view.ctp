<?php $this->Breadcrumbs->add(__d('shop','Stocks'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($stock->title); ?>
<?php $this->Toolbar->addLink(
    __d('shop','Edit {0}', __d('shop','Stock')),
    ['action' => 'edit', $stock->id],
    ['data-icon' => 'edit']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','Delete {0}', __d('shop','Stock')),
    ['action' => 'delete', $stock->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $stock->id)]) ?>

<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Stocks')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Stock')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->startGroup(__d('shop','More')); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Stock Transfers')),
    ['controller' => 'ShopStockTransfers', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Stock Transfer')),
    ['controller' => 'ShopStockTransfers', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Stock Values')),
    ['controller' => 'ShopStockValues', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Stock Value')),
    ['controller' => 'ShopStockValues', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
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
            <td><?= __d('shop','Title') ?></td>
            <td><?= h($stock->title) ?></td>
        </tr>


        <tr>
            <td><?= __d('shop','Id') ?></td>
            <td><?= $this->Number->format($stock->id) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __d('shop','Is Default') ?></td>
            <td><?= $stock->is_default ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>
    </table>
</div>
-->
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __d('shop','Related {0}', __d('shop','ShopStockTransfers')) ?></h4>
    <?php if (!empty($stock->shop_stock_transfers)): ?>
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
                <?= $this->Html->link(__d('shop','View'), ['controller' => 'ShopStockTransfers', 'action' => 'view', $shopStockTransfers->id]) ?>

                <?= $this->Html->link(__d('shop','Edit'), ['controller' => 'ShopStockTransfers', 'action' => 'edit', $shopStockTransfers->id]) ?>

                <?= $this->Form->postLink(__d('shop','Delete'), ['controller' => 'ShopStockTransfers', 'action' => 'delete', $shopStockTransfers->id], ['confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopStockTransfers->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __d('shop','Related {0}', __d('shop','ShopStockValues')) ?></h4>
    <?php if (!empty($stock->shop_stock_values)): ?>
    <table class="ui table">
        <tr>
            <th><?= __d('shop','Id') ?></th>
            <th><?= __d('shop','Shop Stock Id') ?></th>
            <th><?= __d('shop','Shop Product Id') ?></th>
            <th><?= __d('shop','Value') ?></th>
            <th><?= __d('shop','Last Transfer In') ?></th>
            <th><?= __d('shop','Last Transfer Out') ?></th>
            <th><?= __d('shop','Created') ?></th>
            <th><?= __d('shop','Modified') ?></th>
            <th class="actions"><?= __d('shop','Actions') ?></th>
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
                <?= $this->Html->link(__d('shop','View'), ['controller' => 'ShopStockValues', 'action' => 'view', $shopStockValues->id]) ?>

                <?= $this->Html->link(__d('shop','Edit'), ['controller' => 'ShopStockValues', 'action' => 'edit', $shopStockValues->id]) ?>

                <?= $this->Form->postLink(__d('shop','Delete'), ['controller' => 'ShopStockValues', 'action' => 'delete', $shopStockValues->id], ['confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopStockValues->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>

