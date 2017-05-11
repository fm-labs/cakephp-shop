<?php $this->Breadcrumbs->add(__d('shop', 'Shop'), ['_name' => 'shop:admin:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Shop Products'), ['action' => 'index']); ?>

<?php $this->Toolbar->addLink(__d('shop', 'New {0}', __d('shop', 'Shop Product')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Categories')),
    ['controller' => 'ShopCategories', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Category')),
    ['controller' => 'ShopCategories', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php
$this->loadHelper('AdminLte.Box');
?>
<?= $this->assign('title', __d('shop','Shop Products ({0})', $locale)); ?>
<div class="shopProducts index">
    <div class="row">
        <?php $this->Form->create(null); ?>
        <div class="col-md-5">
            <?= $this->Form->input('shop_product_id', [
                'options' => $shopProductsList,
                'label' => false,
                'empty' => '- Quick Search -'
            ]); ?>
        </div>
        <div class="col-md-1">
            <?= $this->Form->button('Go'); ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $this->cell('Backend.DataTable', [[
                'viewVars' => ['shopCategories' => $this->get('shopCategories')],
                'filter' => true,
                'paginate' => true,
                'model' => 'Shop.ShopProducts',
                'data' => $shopProducts,
                'fields' => [
                    'sku',
                    'title' => [
                        'formatter' => function($val, $row, $View) {
                            return $View->Html->link($row->title,
                                ['action' => 'edit', $row->id],
                                ['title' => $View->Url->build($row->url)]
                            );
                        }
                    ],
                    'shop_category_id' => [
                        'formatter' => function($val, $row, $View) {
                            return $row->has('shop_category')
                                ? $View->Html->link($row->shop_category->name, ['controller' => 'ShopCategories', 'action' => 'view', $row->shop_category->id]) :
                                '';
                        }
                    ],
                    'price' => [
                        'formatter' => function($val, $row, $View) {
                            return $View->Number->currency($val, 'EUR');
                        }
                    ],
                    'is_buyable' => [
                        'formatter' => function($val, $row, $View) {
                            return $View->Ui->statusLabel($val);
                        }
                    ],
                    'is_published' => [
                        'formatter' => function($val, $row, $View) {
                            return $View->Ui->statusLabel($val);
                        },
                        'style' => 'text-align: right;'
                    ],
                ],
                'rowActions' => [
                    [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
                    [__d('shop','Edit English version'), ['action' => 'edit', ':id', 'locale' => 'en'], ['class' => 'edit']],
                    [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
                ]
            ]]); ?>
        </div>
    </div>
</div>