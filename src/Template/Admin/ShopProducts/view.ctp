<?php $this->Html->addCrumb(__d('shop', 'Shop Products'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($shopProduct->title); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Edit {0}', __d('shop', 'Shop Product')),
    ['action' => 'edit', $shopProduct->id],
    ['data-icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Delete {0}', __d('shop', 'Shop Product')),
    ['action' => 'delete', $shopProduct->id],
    ['data-icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopProduct->id)]) ?>

<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__d('shop', 'More')); ?>
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
<?= $this->Toolbar->endGroup(); ?>
<div class="shopProducts view">
    <h2 class="ui header">
        <?= h($shopProduct->title) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __d('shop', 'Label'); ?></th>
            <th><?= __d('shop', 'Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __d('shop', 'Shop Category') ?></td>
            <td><?= $shopProduct->has('shop_category') ? $this->Html->link($shopProduct->shop_category->name, ['controller' => 'ShopCategories', 'action' => 'view', $shopProduct->shop_category->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Sku') ?></td>
            <td><?= h($shopProduct->sku) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Title') ?></td>
            <td><?= h($shopProduct->title) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Slug') ?></td>
            <td><?= h($shopProduct->slug) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Preview Image File') ?></td>
            <td><?= h($shopProduct->preview_image_file) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Featured Image File') ?></td>
            <td><?= h($shopProduct->featured_image_file) ?></td>
        </tr>


        <tr>
            <td><?= __d('shop', 'Id') ?></td>
            <td><?= $this->Number->format($shopProduct->id) ?></td>
        </tr>


        <tr class="date">
            <td><?= __d('shop', 'Publish Start Date') ?></td>
            <td><?= h($shopProduct->publish_start_date) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop', 'Publish End Date') ?></td>
            <td><?= h($shopProduct->publish_end_date) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __d('shop', 'Is Published') ?></td>
            <td><?= $shopProduct->is_published ? __d('shop', 'Yes') : __d('shop', 'No'); ?></td>
        </tr>
        <tr class="text">
            <td><?= __d('shop', 'Teaser Html') ?></td>
            <td><?= $this->Text->autoParagraph(h($shopProduct->teaser_html)); ?></td>
        </tr>
        <tr class="text">
            <td><?= __d('shop', 'Desc Html') ?></td>
            <td><?= $this->Text->autoParagraph(h($shopProduct->desc_html)); ?></td>
        </tr>
    </table>
</div>
