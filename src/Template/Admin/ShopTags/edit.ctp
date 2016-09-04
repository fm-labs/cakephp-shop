<?php $this->Html->addCrumb(__d('shop', 'Shop Tags'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('shop', 'Edit {0}', __d('shop', 'Shop Tag'))); ?>
<?= $this->Toolbar->addPostLink(
    __d('shop', 'Delete'),
    ['action' => 'delete', $shopTag->id],
    ['icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopTag->id)]
)
?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Tags')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products Tags')),
    ['controller' => 'ShopProductsTags', 'action' => 'index'],
    ['icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Products Tag')),
    ['controller' => 'ShopProductsTags', 'action' => 'add'],
    ['icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop', 'Edit {0}', __d('shop', 'Shop Tag')) ?>
    </h2>
    <?= $this->Form->create($shopTag); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('group');
                echo $this->Form->input('name');
                echo $this->Form->input('class');
                echo $this->Form->input('published');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop', 'Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>