<?php $this->Breadcrumbs->add(__d('shop', 'Shop Tags')); ?>

<?php $this->Toolbar->addLink(__d('shop', 'New {0}', __d('shop', 'Shop Tag')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products Tags')),
    ['controller' => 'ShopProductsTags', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Products Tag')),
    ['controller' => 'ShopProductsTags', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<div class="shopTags index">
    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Shop.ShopTags',
        'data' => $shopTags,
        'fields' => [
            'id',
            'group',
            'name',
            'class',
            'published',
        ],
        'rowActions' => [
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>
</div>
