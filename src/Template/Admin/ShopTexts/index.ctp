<?php $this->Html->addCrumb(__d('shop', 'Shop Texts')); ?>

<?php $this->Toolbar->addLink(__d('shop', 'New {0}', __d('shop', 'Shop Text')), ['action' => 'add'], ['icon' => 'plus']); ?>
<div class="shopTexts index">
    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Shop.ShopTexts',
        'data' => $shopTexts,
        'fields' => [
            'id',
            'model',
            'model_id',
            'model_scope',
            'locale',
            'format',
            'class',
        ],
        'rowActions' => [
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Edit Iframe'), ['action' => 'edit_iframe', ':id'], ['class' => 'link-link-frame-modal']],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>
</div>
