<?php $this->Breadcrumbs->add(__d('shop','Shop Addresses')); ?>

<?php $this->Toolbar->addLink(__d('shop','New {0}', __d('shop','Shop Address')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
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
<div class="shopAddresses index">
    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Shop.ShopAddresses',
        'data' => $shopAddresses,
        'fields' => [
            'id',
            'shop_customer_id' => [
                'formatter' => function($val, $row) {
                    return ($row->has('shop_customer'))
                        ? $this->Html->link($row->shop_customer->display_name, ['controller' => 'ShopCustomers', 'action' => 'view', $row->shop_customer->id])
                        : '';
                }
            ],
            'title' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['action' => 'edit', $row->id]);
                }
            ],
            'name',
            'street',
            'zipcode',
            'city',
            'country'
        ],
        'rowActions' => [
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>
</div>
