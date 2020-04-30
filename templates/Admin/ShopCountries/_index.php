<?php $this->Breadcrumbs->add(__d('shop', 'Shop Countries')); ?>

<?php $this->Toolbar->addLink(__d('shop', 'New {0}', __d('shop', 'Shop Country')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<div class="shopCountries index">

    <?php $fields = [
    'id','iso2','iso3','name','name_de','priority','is_published',    ] ?>
    <?= $this->cell('Admin.DataTable', [[
        'paginate' => true,
        'model' => 'Shop.ShopCountries',
        'data' => $shopCountries,
        'fields' => $fields,
        'debug' => true,
        'rowActions' => [
            [__d('shop','View'), ['action' => 'view', ':id'], ['class' => 'view']],
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>

</div>

