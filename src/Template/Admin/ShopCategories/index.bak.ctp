<?php $this->Html->addCrumb(__d('shop', 'Shop Categories')); ?>

<?php $this->Toolbar->addLink(__d('shop', 'New {0}', __d('shop', 'Shop Category')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<div class="shopCategories index">
    <table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('parent_id') ?></th>
            <th><?= $this->Paginator->sort('name') ?></th>
            <th class="actions"><?= __d('shop', 'Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($shopCategories as $shopCategory): ?>
        <tr>
            <td><?= $this->Number->format($shopCategory->id) ?></td>
            <td>
                <?= $shopCategory->has('parent_shop_category') ? $this->Html->link($shopCategory->parent_shop_category->name, ['controller' => 'ShopCategories', 'action' => 'view', $shopCategory->parent_shop_category->id]) : '' ?>
            </td>
            <td><?= $this->Html->link($shopCategory->name, ['action' => 'edit', $shopCategory->id]) ?></td>
            <td class="actions">
                <?php
                $menu = new Backend\Lib\Menu\Menu();
                $menu->add(__d('shop', 'View'), ['action' => 'view', $shopCategory->id]);

                $dropdown = $menu->add('Dropdown');
                $dropdown->getChildren()->add(
                    __d('shop', 'Delete'),
                    ['action' => 'delete', $shopCategory->id],
                    ['data-icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopCategory->id)]
                );
                ?>
                <?= $this->element('Backend.Table/table_row_actions', ['menu' => $menu]); ?>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
    </table>
    <?= $this->element('Backend.Pagination/default'); ?>
</div>
