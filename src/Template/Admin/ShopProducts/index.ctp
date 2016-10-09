<?php $this->Html->addCrumb(__d('shop', 'Shop Products')); ?>

<?php $this->Toolbar->addLink(__d('shop', 'New {0}', __d('shop', 'Shop Product')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Categories')),
    ['controller' => 'ShopCategories', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Category')),
    ['controller' => 'ShopCategories', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<div class="shopProducts index">
    <h2>Products (<?= h($locale); ?>)</h2>

    <div class="panel panel-default">
        <div class="panel-heading">
            Quick Search
        </div>
        <div class="panel-body">
            <?= $this->Form->create(null, ['id' => 'quickfinder', 'action' => 'quick', 'class' => 'no-ajax']); ?>
            <?= $this->Form->input('shop_product_id', [
                'options' => $shopProductsList,
                'label' => false,
                'empty' => '- Quick Search -'
            ]); ?>
            <?= $this->Form->button('Go'); ?>
            <?= $this->Form->end() ?>
        </div>
    </div>

    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Shop.ShopProducts',
        'data' => $shopProducts,
        'fields' => [
            'sku',
            'title' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($row->title,
                        ['action' => 'edit', $row->id],
                        ['title' => $this->Url->build($row->url)]
                    );
                }
            ],
            'shop_category_id' => [
                'formatter' => function($val, $row) {
                    return $row->has('shop_category')
                        ? $this->Html->link($row->shop_category->name, ['controller' => 'ShopCategories', 'action' => 'view', $row->shop_category->id]) :
                        '';
                }
            ],
            'price' => [
                'formatter' => function($val, $row) {
                    return $this->Number->currency($val, 'EUR');
                }
            ],
            'is_buyable' => [
                'formatter' => function($val, $row) {
                    return $this->Ui->statusLabel($val);
                }
            ],
            'is_published' => [
                'formatter' => function($val, $row) {
                    return $this->Ui->statusLabel($val);
                },
                'style' => 'text-align: right;'
            ],
        ],
        'rowActions' => [
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Edit English version'), ['action' => 'edit', ':id', 'locale' => 'en'], ['class' => 'edit']],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>
</div>
