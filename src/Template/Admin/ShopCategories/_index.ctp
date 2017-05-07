<?php //$this->extend('base'); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Shop'), ['_name' => 'shop:admin:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Shop Categories')); ?>
<?php $this->Toolbar->addLink(__d('shop', 'New {0}', __d('shop', 'Category')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['data-icon' => 'plus', 'class' => 'link-frame-modal']
) ?>
<?php $this->Toolbar->addLink(__d('shop', 'Sort'),
    ['plugin' => 'Backend', 'controller' => 'Tree', 'action' => 'index', 'model' => 'Shop.ShopCategories'],
    ['class' => 'link-modal-frame', 'data-icon' => 'sitemap']); ?>
<?php $this->assign('title', __d('shop','Shop Categories')); ?>
<?php $shopCategoriesTree = $this->get('shopCategoriesTree', []); ?>

<?= $this->cell('Backend.DataTable', [[
    'paginate' => true,
    'model' => 'Shop.ShopCategories',
    'data' => $shopCategories,
    'fieldsWhitelist' => ['name', 'view_template', 'is_published'],
    'fields' => [
        'name' => [
            'formatter' => function($val, $row) use ($shopCategoriesTree) {
                return $this->Html->link($shopCategoriesTree[$row->id],
                    ['action' => 'edit', $row->id],
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
        [__d('shop','Preview'), ['action' => 'preview', ':id'], ['class' => 'preview external', 'target' => '_blank']],
        [__d('shop','Move Up'), ['action' => 'moveUp', ':id'], ['class' => 'move-up']],
        [__d('shop','Move Down'), ['action' => 'moveDown', ':id'], ['class' => 'move-down']],
        [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
    ]
]]);

debug($shopCategories);
?>