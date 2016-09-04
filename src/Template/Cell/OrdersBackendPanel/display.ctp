<div class="panel panel-default">
    <div class="panel-heading">Latest Orders</div>
    <div class="panel-body">
        <?= $this->cell('Backend.DataTable', [[
            'paginate' => false,
            'model' => 'Shop.ShopOrders',
            'data' => $orders,
            'fields' => [
                'id' => [
                    'formatter' => function($val) {
                        return $this->Html->link($val, ['plugin' => 'Shop', 'controller' => 'ShopOrders', 'action' => 'view', $val], ['class' => 'link-frame']);
                    }
                ],
                'submitted',
                'billing_address.name',
                'order_value_total' => [
                    'formatter' => function($val) {
                        return $this->Number->currency($val, 'EUR');
                    }
                ]
            ],
            'rowActions' => false
        ]]);
        ?>
    </div>
    <div class="panel-footer">
        <?= $this->Html->link(__d('shop','View all orders'),
            ['plugin' => 'Shop', 'controller' => 'ShopOrders', 'action' => 'index'],
            ['class' => 'btn btn-default link-frame']); ?>
    </div>
</div>