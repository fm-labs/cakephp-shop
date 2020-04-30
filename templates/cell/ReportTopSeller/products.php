<?php
$this->loadHelper('time');
$title = sprintf("%s - %s", $this->Time->format($dateStart, "dd.MM.YYYY"), $this->Time->format($dateEnd, "dd.MM.YYYY"));
?>
<?php
    echo $this->cell('Admin.DataTable', [[
        'paginate' => false,
        'filter' => false,
        'model' => null,
        'title' => null, //$title,
        'data' => $topsellers, //$topsellers,
        //'fieldsWhitelist' => true,
        'fields' => [
            'title' => [
                'label' => __d('shop', 'Product'),
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['plugin' => 'Shop', 'controller' => 'ShopProducts', 'action' => 'edit', $row['key']]);
                }
            ],
            //'sku' => [],
            'count' => [
                'label' => __d('shop', 'Sales')
            ]
        ],
        /*
        */
        'rowActions' => false
    ]]);
    try {
} catch (Exception $ex) {
    debug($ex);
    echo h($ex->getMessage());
}