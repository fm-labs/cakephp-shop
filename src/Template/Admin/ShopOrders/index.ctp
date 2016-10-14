<?php $this->extend('/Admin/Base/index'); ?>
<?php $this->loadHelpers('Number'); ?>
<?php $this->Html->addCrumb(__d('shop','Shop'), ['_name' => 'shop:admin:index']); ?>
<?php $this->Html->addCrumb(__d('shop','Shop Orders'), ['action' => 'index']); ?>

<?php $this->Toolbar->addLink(__d('shop','New {0}', __d('shop','Shop Order')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Customers')),
    ['controller' => 'ShopCustomers', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Customer')),
    ['controller' => 'ShopCustomers', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Order Items')),
    ['controller' => 'ShopOrderItems', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<div class="shopOrders index">


    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Shop.ShopOrders',
        'data' => $shopOrders,
        'fields' => [
            'id' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['action' => 'view', $row->id]);
                }
            ],
            'submitted' => [
                'formatter' => ['date' => ['format' => 'dd.MM.yyyy']]
            ],
            'nr_formatted' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['action' => 'view', $row->id]);
                }
            ],
            'billing_address.name',
            'order_value_total' => [
                'formatter' => ['currency' => ['currency' =>  'EUR']],
            ],
            'status' => [
                'formatter' => function($val) {
                    return $this->Ui->statusLabel($val);
                }
            ]
        ],
        'rowActions' => [
            [__d('shop','View'), ['action' => 'view', ':id'], ['class' => 'view']],
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Send Owner Order Notification'), ['action' => 'emailOwnerOrderNotify', ':id'], []],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>
</div>
