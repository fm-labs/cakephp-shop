<?php $this->Breadcrumbs->add(__d('shop', 'Shop Products'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'New {0}', __d('shop', 'Shop Product'))); ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Categories')),
    ['controller' => 'ShopCategories', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Category')),
    ['controller' => 'ShopCategories', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop', 'Add {0}', __d('shop', 'Shop Product')) ?>
    </h2>
    <?= $this->Form->create($shopProduct, ['class' => 'no-ajax']); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('shop_category_id', ['options' => $shopCategories, 'empty' => true]);
                echo $this->Form->input('sku');
                echo $this->Form->input('title');
                echo $this->Form->hidden('slug');
                //echo $this->Form->input('desc_short_text');
                //echo $this->Form->input('desc_long_text');
                //echo $this->Form->input('preview_image_file');
                //echo $this->Form->input('featured_image_file');
                echo $this->Form->hidden('is_published', ['value' => 0]);
                echo $this->Form->hidden('publish_start_date', ['default' => null]);
                echo $this->Form->hidden('publish_end_date', ['default' => null]);
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop', 'Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>