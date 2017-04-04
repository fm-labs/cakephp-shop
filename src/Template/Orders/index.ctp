<?php $this->Breadcrumbs->add(__d('shop','Customer'), ['controller' => 'Customer', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop Orders'), ['action' => 'index']); ?>

<div class="shopOrders index container">


    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Shop.ShopOrders',
        'data' => $shopOrders,
        'class' => 'table table-condensed table-striped table-hover',
        'fields' => [
            'id' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['action' => 'view', $row->id]);
                }
            ],
            'submitted' => [
                //'formatter' => ['date' => ['_format' => 'd.M.y']]
            ],
            'nr_formatted' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['action' => 'view', $row->id]);
                }
            ],
            'billing_address.name',
            'order_value_total' => [
                'class' => 'right',
                'formatter' => ['currency' => ['currency' =>  'EUR']],
            ],
            'status' => []
        ],
        'rowActions' => [
            [__d('shop','View'), ['action' => 'view', ':uuid'], ['class' => 'view']],
            [__d('shop','Cancel'), ['action' => 'cancel', ':uuid'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to cancel order # {0}?', ':nr_formatted')]]
        ]
    ]]);
    ?>
</div>
