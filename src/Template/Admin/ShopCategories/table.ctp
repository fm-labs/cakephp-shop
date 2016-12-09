<?php $this->Breadcrumbs->add(__d('shop', 'Shop Categories')); ?>

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
<?= $this->Toolbar->addLink(
    __d('shop', 'Repair'),
    ['action' => 'repair'],
    ['data-icon' => 'configure', 'confirm' => __d('shop', 'Do you really want to repair the category tree?')]
) ?>
<div class="shopCategories index">

    <!-- Quick Search -->
    <div class="ui segment">
        <div class="ui form">
            <?= $this->Form->create(null, ['id' => 'quickfinder', 'action' => 'quick']); ?>
            <?= $this->Form->input('shop_category_id', [
                'options' => $shopCategoriesTree,
                'label' => false,
                'empty' => '- Quick Search -'
            ]); ?>
            <?= $this->Form->button('Go'); ?>
            <?= $this->Form->end() ?>
        </div>
    </div>

    <table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('name') ?></th>
            <th><?= $this->Paginator->sort('view_template') ?></th>
            <th><?= $this->Paginator->sort('is_published') ?></th>
            <th class="actions"><?= __d('shop', 'Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($shopCategories as $shopCategory): ?>
        <tr>
            <td><?= $this->Number->format($shopCategory->id) ?></td>
            <td>
                <?= $this->Html->link(
                    $shopCategoriesTree[$shopCategory->id],
                    ['action' => 'edit', $shopCategory->id],
                    ['title' => $this->Url->build($shopCategory->url)]
                ) ?>
                <!--
                <br />
                <small><?= $this->Html->link($shopCategory->url); ?></small>
                -->
            </td>
            <td><?= h($shopCategory->view_template); ?></td>
            <td><?= $this->Ui->statusLabel($shopCategory->is_published); ?></td>
            <td class="actions">
                <?php
                $menu = new Backend\Lib\Menu\Menu();
                $menu->add(__d('shop', 'View'), ['action' => 'view', $shopCategory->id]);

                $dropdown = $menu->add('Dropdown');
                $dropdown->getChildren()->add(
                    __d('shop', 'Preview'),
                    ['action' => 'preview', $shopCategory->id],
                    ['data-icon' => 'eye', 'target' => 'preview']
                );
                $dropdown->getChildren()->add(
                    __d('shop', 'Move Up'),
                    ['action' => 'moveUp', $shopCategory->id],
                    ['data-icon' => 'arrow up']
                );
                $dropdown->getChildren()->add(
                    __d('shop', 'Move Down'),
                    ['action' => 'moveDown', $shopCategory->id],
                    ['data-icon' => 'arrow down']
                );
                $dropdown->getChildren()->add(
                    __d('shop', 'Delete'),
                    ['action' => 'delete', $shopCategory->id],
                    ['data-icon' => 'trash', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopCategory->id)]
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
