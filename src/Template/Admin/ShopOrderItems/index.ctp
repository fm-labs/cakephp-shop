<?php $this->Breadcrumbs->add(__d('shop','Shop Order Items')); ?>
<?php $this->loadHelper('Banana.Status'); ?>
<?php $this->Toolbar->addLink(__d('shop','New {0}', __d('shop','Shop Order Item')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Orders')),
    ['controller' => 'ShopOrders', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Order')),
    ['controller' => 'ShopOrders', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<div class="shopOrderItems index">


    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Shop.ShopOrderItems',
        'data' => $shopOrderItems,
        'class' => 'table table-condensed table-striped table-hover',
        'fields' => [
            'id' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['action' => 'view', $row->id]);
                }
            ],
            'product_sku' => [
                'formatter' => function($val, $row) {
                    return ($val) ?: $row->getProduct()->getSku();
                }
            ],
            'product_title' => [
                'formatter' => function($val, $row) {
                    $val = ($val) ?: $row->getProduct()->getTitle();
                    return $this->Html->link($val, $row->getProduct()->getAdminUrl(), ['class' => 'link-modal-frame']);
                }
            ],
            'amount' => ['formatter' => function($val, $row) {
                return sprintf("%d %s", $val, $row->unit);
            }],
            /*
            'value_tax' => ['formatter' => function($val, $row) use ($shopOrder) {
                return $this->Number->currency($val, $shopOrder->currency);
            }],
            'value_net' => ['formatter' => function($val, $row) use ($shopOrder) {
                return $this->Number->currency($val, $shopOrder->currency);
            }],
            */
            'value' => ['title' => __('Total'), 'formatter' => function($val, $row) {
                $val = ($val) ?: $row->value_net + $row->value_tax;
                return $this->Number->currency($val, $row->currency);

            }],
            'status' => ['formatter' => function($val) {
                return $this->Status->label($val);
            }],
        ],
        'rowActions' => [
            [__d('shop','View'), ['action' => 'view', ':id'], ['class' => 'view']],
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            //[__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ],
        'reduce' => [
            'value' => [
                'callable' => function($val, $row, &$stack) {
                    if (!isset($stack['value'])) {
                        $stack['value'] = 0;
                    }
                    $stack['value'] += ($row->value_net + $row->value_tax);
                },
                'formatter' => function($val) {
                    return $this->Number->currency($val, 'EUR');
                }]
        ]
    ]]);
    ?>

    <!--
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
                    ['data-icon' => 'edit']
                );
                $dropdown->getChildren()->add(
                    __d('shop','Delete'),
                    ['action' => 'delete', $shopOrderItem->id],
                    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopOrderItem->id)]
                );
                ?>
                <?= $this->element('Backend.Table/table_row_actions', ['menu' => $menu]); ?>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
    </table>
    <?= $this->element('Backend.Pagination/default'); ?>
    -->
</div>
