<?= $this->cell('Backend.DataTable', [[
    'paginate' => false,
    'model' => null,
    'title' => 'Data of last 90 days',
    'data' => $topsellers,
    'fieldsWhitelist' => true,
    'fields' => [
        'title' => [
            'label' => __('Product'),
            'formatter' => function($val, $row) {
                return $this->Html->link($val, ['plugin' => 'Shop', 'controller' => 'ShopProduct', 'action' => 'view', $row['key']]);
            }
        ],
        //'sku' => [],
        'count' => [
            'label' => __('Sales')
        ]
    ],
    'rowActions' => false
]]);