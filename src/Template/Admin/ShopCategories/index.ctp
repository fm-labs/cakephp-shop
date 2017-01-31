<?php //$this->extend('base'); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Shop'), ['_name' => 'shop:admin:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Shop Categories')); ?>
<?php $this->Toolbar->addLink(__d('shop', 'New {0}', __d('shop', 'Shop Category')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['data-icon' => 'plus', 'class' => 'link-frame-modal']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php //$this->extend('Backend./Base/index_2col'); ?>
<?php $this->assign('title', __d('shop','Shop Categories')); ?>

<?php $this->start('col-left'); ?>
Hello from Left column
<?php $this->end(); ?>


<?= $this->cell('Backend.DataTable', [[
    'paginate' => true,
    'model' => 'Shop.ShopCategories',
    'data' => $shopCategories,
    'fields' => [
        /*
        'id',
        'level',
        'parent_id' => [
            'formatter' => function($val, $row) {
                return $row->has('parent_category')
                    ? $this->Html->link($row->parent_category->name, ['controller' => 'ShopCategories', 'action' => 'view', $row->parent_id]) :
                    '';
            }
        ],
        */
        'name' => [
            'formatter' => function($val, $row) {
                return $this->Html->link($row->name,
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