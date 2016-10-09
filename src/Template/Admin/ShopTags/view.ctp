<?php $this->Html->addCrumb(__d('shop', 'Shop Tags'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($shopTag->name); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Edit {0}', __d('shop', 'Shop Tag')),
    ['action' => 'edit', $shopTag->id],
    ['data-icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Delete {0}', __d('shop', 'Shop Tag')),
    ['action' => 'delete', $shopTag->id],
    ['data-icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopTag->id)]) ?>

<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Tags')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Tag')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__d('shop', 'More')); ?>
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
<?= $this->Toolbar->endGroup(); ?>
<div class="shopTags view">
    <h2 class="ui header">
        <?= h($shopTag->name) ?>
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
            <td><?= __d('shop', 'Id') ?></td>
            <td><?= $this->Number->format($shopTag->id) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Group') ?></td>
            <td><?= h($shopTag->group) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Name') ?></td>
            <td><?= h($shopTag->name) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Class') ?></td>
            <td><?= h($shopTag->class) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Published') ?></td>
            <td><?= h($shopTag->published) ?></td>
        </tr>



    </table>
</div>
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __d('shop', 'Related {0}', __d('shop', 'ShopProductsTags')) ?></h4>
    <?php if (!empty($shopTag->shop_products_tags)): ?>
    <table class="ui table">
        <tr>
            <th><?= __d('shop', 'Id') ?></th>
            <th><?= __d('shop', 'Shop Product Id') ?></th>
            <th><?= __d('shop', 'Shop Tag Id') ?></th>
            <th class="actions"><?= __d('shop', 'Actions') ?></th>
        </tr>
        <?php foreach ($shopTag->shop_products_tags as $shopProductsTags): ?>
        <tr>
            <td><?= h($shopProductsTags->id) ?></td>
            <td><?= h($shopProductsTags->shop_product_id) ?></td>
            <td><?= h($shopProductsTags->shop_tag_id) ?></td>

            <td class="actions">
                <?= $this->Html->link(__d('shop', 'View'), ['controller' => 'ShopProductsTags', 'action' => 'view', $shopProductsTags->id]) ?>

                <?= $this->Html->link(__d('shop', 'Edit'), ['controller' => 'ShopProductsTags', 'action' => 'edit', $shopProductsTags->id]) ?>

                <?= $this->Form->postLink(__d('shop', 'Delete'), ['controller' => 'ShopProductsTags', 'action' => 'delete', $shopProductsTags->id], ['confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopProductsTags->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
