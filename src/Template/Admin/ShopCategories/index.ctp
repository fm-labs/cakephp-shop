<?php //$this->extend('base'); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Shop'), ['_name' => 'shop:admin:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Shop Categories')); ?>
<?php $this->Toolbar->addLink(__d('shop', 'New {0}', __d('shop', 'Shop Category')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['data-icon' => 'plus', 'class' => 'link-frame-modal']
) ?>
<?php $this->assign('title', __d('shop','Shop Categories')); ?>
<?php $shopCategoriesTree = $this->get('shopCategoriesTree', []); ?>

<?= $this->cell('Backend.DataTable', [[
    'paginate' => true,
    'model' => 'Shop.ShopCategories',
    'data' => $shopCategories,
    'fields' => [
        'name' => [
            'formatter' => function($val, $row) use ($shopCategoriesTree) {
                return $this->Html->link($shopCategoriesTree[$row->id],
                    ['action' => 'manage', $row->id],
                    ['title' => $this->Url->build($row->url)]
                );
            }
        ],
        'view_template',
        'is_published' => [
            'formatter' => function($val, $row) {
                return $this->Ui->statusLabel($val);
            },
            'style' => 'text-align: right;'
        ],
    ],
    'rowActions' => [
        [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
        [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
    ]
]]); ?>