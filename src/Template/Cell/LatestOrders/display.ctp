<?= $this->cell('Backend.DataTable', [[
    'paginate' => false,
    'model' => 'Shop.ShopOrders',
    'data' => $orders,
    'fieldsWhitelist' => true,
    'fields' => [
        'id' => [
            'formatter' => function($val) {
                return $this->Html->link($val, ['plugin' => 'Shop', 'controller' => 'ShopOrders', 'action' => 'view', $val], ['class' => 'link-frame']);
            }
        ],
        'submitted' => [],
        'nr_formatted' => [],
        'order_value_total' => [
            'formatter' => function($val) {
                return $this->Number->currency($val, 'EUR');
            }
        ]
    ],
    'rowActions' => false
]]);
?>