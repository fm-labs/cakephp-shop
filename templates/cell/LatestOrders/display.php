<?php
$this->loadHelper('Number');
$this->loadHelper('Cupcake.Status');

echo $this->cell('Admin.DataTable', [[
    'paginate' => false,
    'filter' => false,
    'model' => 'Shop.ShopOrders',
    'data' => $orders,
    'fieldsWhitelist' => true,
    'fields' => [
        'submitted' => [],
        'nr_formatted' => [
            'formatter' => function($val, $row) {
                return $this->Html->link($val, ['plugin' => 'Shop', 'controller' => 'ShopOrders', 'action' => 'detailview', $row['id']], ['class' => 'link-frame']);
            }
        ],
        'order_value_total' => [
            'formatter' => function($val) {
                return $this->Number->currency($val, 'EUR');
            }
        ],
        'status__status' => [
            'formatter' => 'status'
        ]
    ],
    'rowActions' => false
]]);
