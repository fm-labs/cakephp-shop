<?php $this->Html->addCrumb(__d('shop','Shop Order Items')); ?>

<?php $this->Toolbar->addLink(__d('shop','New {0}', __d('shop','Shop Order Item')), ['action' => 'add'], ['icon' => 'plus']); ?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Orders')),
    ['controller' => 'ShopOrders', 'action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Order')),
    ['controller' => 'ShopOrders', 'action' => 'add'],
    ['icon' => 'plus']
) ?>
<div class="shopOrderItems index">
    <table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('shop_order_id') ?></th>
            <th><?= $this->Paginator->sort('refscope') ?></th>
            <th><?= $this->Paginator->sort('refid') ?></th>
            <th><?= $this->Paginator->sort('title') ?></th>
            <th><?= $this->Paginator->sort('amount') ?></th>
            <th><?= $this->Paginator->sort('unit') ?></th>
            <th class="actions"><?= __d('shop','Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($shopOrderItems as $shopOrderItem): ?>
        <tr>
            <td><?= $this->Number->format($shopOrderItem->id) ?></td>
            <td>
                <?= $shopOrderItem->has('shop_order') ? $this->Html->link($shopOrderItem->shop_order->title, ['controller' => 'ShopOrders', 'action' => 'view', $shopOrderItem->shop_order->id]) : '' ?>
            </td>
            <td><?= h($shopOrderItem->refscope) ?></td>
            <td><?= $this->Number->format($shopOrderItem->refid) ?></td>
            <td><?= h($shopOrderItem->title) ?></td>
            <td><?= $this->Number->format($shopOrderItem->amount) ?></td>
            <td><?= h($shopOrderItem->unit) ?></td>
            <td class="actions">
                <?php
                $menu = new Backend\Lib\Menu\Menu();
                $menu->add(__d('shop','View'), ['action' => 'view', $shopOrderItem->id]);

                $dropdown = $menu->add('Dropdown');
                $dropdown->getChildren()->add(
                    __d('shop','Edit'),
                    ['action' => 'edit', $shopOrderItem->id],
                    ['icon' => 'edit']
                );
                $dropdown->getChildren()->add(
                    __d('shop','Delete'),
                    ['action' => 'delete', $shopOrderItem->id],
                    ['icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopOrderItem->id)]
                );
                ?>
                <?= $this->element('Backend.Table/table_row_actions', ['menu' => $menu]); ?>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
    </table>
    <?= $this->element('Backend.Pagination/default'); ?>
</div>
