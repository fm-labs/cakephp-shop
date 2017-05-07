<?php $this->Breadcrumbs->add(__d('shop','Shop Orders')); ?>

<?php $this->Toolbar->addLink(__d('shop','New {0}', __d('shop','Shop Order')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Customers')),
    ['controller' => 'ShopCustomers', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Customer')),
    ['controller' => 'ShopCustomers', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Order Items')),
    ['controller' => 'ShopOrderItems', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<div class="shopOrders index">
    <table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('submitted') ?></th>
            <th><?= $this->Paginator->sort('nr') ?></th>
            <th><?= $this->Paginator->sort('shop_customer_id') ?></th>
            <th><?= $this->Paginator->sort('billing_last_name') ?></th>
            <th><?= $this->Paginator->sort('shipping_last_name') ?></th>
            <th><?= $this->Paginator->sort('status') ?></th>
            <th class="actions"><?= __d('shop','Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($shopOrders as $shopOrder): ?>
        <tr>
            <td><?= $this->Number->format($shopOrder->id) ?></td>
            <td><?= h($shopOrder->submitted) ?></td>
            <td><?= $this->Number->format($shopOrder->nr) ?></td>
            <td>
                <?= $shopOrder->has('shop_customer') ? $this->Html->link($shopOrder->shop_customer->display_name, ['controller' => 'ShopCustomers', 'action' => 'view', $shopOrder->shop_customer->id]) : '' ?>
            </td>
            <td><?= h($shopOrder->billing_last_name) ?></td>
            <td><?= h($shopOrder->shipping_last_name) ?></td>
            <td><?= h($shopOrder->status) ?></td>
            <td class="actions">
                <?php
                $menu = new Backend\Lib\Menu\Menu();
                $menu->add(__d('shop','View'), ['action' => 'view', $shopOrder->id]);

                $dropdown = $menu->add('Dropdown');
                $dropdown->getChildren()->add(
                    __d('shop','Edit'),
                    ['action' => 'edit', $shopOrder->id],
                    ['data-icon' => 'edit']
                );
                $dropdown->getChildren()->add(
                    __d('shop','Send Owner Order Notification'),
                    ['action' => 'emailOwnerOrderNotify', $shopOrder->id],
                    ['data-icon' => 'edit']
                );
                $dropdown->getChildren()->add(
                    __d('shop','Delete'),
                    ['action' => 'delete', $shopOrder->id],
                    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopOrder->id)]
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
