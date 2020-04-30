<div class="shop categories related products">

    <?= $this->cell('Admin.DataTable', [[
        'paginate' => false,
        'model' => 'Shop.ShopProducts',
        'data' => $this->get('shopProducts'),
        'fieldsWhitelist' => true,
        'fields' => [
            //'id' => [],
            'sku' => [],
            /*
            'parent' => [
                'formatter' => function($val) {
                    if ($val) {
                        return $this->Html->link($val->title, ['action' => 'edit', $val->id]);
                    }
                }
            ],
            */
            'title' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['controller' => 'ShopProducts', 'action' => 'edit', $row->id], ['class' => 'link-frame']);
                }
            ],
            'is_buyable' => [
                'formatter' => function($val, $row) {
                    $toggleUrl = ['controller' => 'ShopProducts', 'action' => 'toggle', $row->id, 'is_buyable'];
                    return $this->Ui->statusLabel($val, [
                        'data-toggle-url' => $this->Html->Url->build($toggleUrl)
                    ]);
                }
            ],
            'is_published' => [
                'formatter' => function($val, $row) {
                    $toggleUrl = ['controller' => 'ShopProducts', 'action' => 'toggle', $row->id, 'is_published'];
                    return $this->Ui->statusLabel($val, [
                        'data-toggle-url' => $this->Html->Url->build($toggleUrl)
                    ]);
                }
            ],
        ],
        'rowActions' => [
            //[__d('shop','Edit'), ['controller' => 'ShopProducts', 'action' => 'edit', ':id'], ['class' => 'edit link-frame']],
            //[__d('shop','Delete'), ['controller' => 'ShopProducts', 'action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>

    <hr />
    <?= $this->Html->link(
        __d('shop', 'New {0}', __d('shop', 'Shop Product')),
        ['controller' => 'ShopProducts', 'action' => 'add', 'shop_category_id' => $shopCategory->id],
        ['data-icon' => 'plus', 'class' => 'btn btn-default btn-add']
    ) ?>
</div>
