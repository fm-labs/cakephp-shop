<?php $this->Breadcrumbs->add(__d('shop', 'Shop Categories'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'New {0}', __d('shop', 'Shop Category'))); ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Categories')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Parent Shop Categories')),
    ['controller' => 'ShopCategories', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Parent Shop Category')),
    ['controller' => 'ShopCategories', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop', 'Add {0}', __d('shop', 'Shop Category')) ?>
    </h2>
    <?= $this->Form->create($shopCategory, ['class' => 'no-ajax']); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                echo $this->Form->control('parent_id', ['options' => $parentShopCategories, 'empty' => '- No parent -']);
                echo $this->Form->control('name');
                echo $this->Form->hidden('slug');
                echo $this->Form->hidden('is_published', ['default' => 0]);
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop', 'Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>