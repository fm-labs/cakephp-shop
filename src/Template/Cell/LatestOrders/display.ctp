<?= $this->cell('Backend.DataTable', [[
    'paginate' => false,
    'filter' => false,
    'model' => 'Shop.ShopOrders',
    'data' => $orders,
    'fieldsWhitelist' => true,
    'fields' => [
        'submitted' => [],
        'nr_formatted' => [
            'formatter' => function($val) {
                return $this->Html->link($val, ['plugin' => 'Shop', 'controller' => 'ShopOrders', 'action' => 'view', $val], ['class' => 'link-frame']);
            }
        ],
        'order_value_total' => [
            'formatter' => function($val) {
                return $this->Number->currency($val, 'EUR');
            }
        ]
    ],
    'rowActions' => false
]]);
?>