<?php $this->Breadcrumbs->add(__d('shop', 'Shop Texts'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Edit {0}', __d('shop', 'Shop Text'))); ?>
<?php $this->Toolbar->addPostLink(
    __d('shop', 'Delete'),
    ['action' => 'delete', $shopText->id],
    ['data-icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopText->id)]
)
?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Texts')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop', 'Edit {0}', __d('shop', 'Shop Text')) ?>
    </h2>
    <?= $this->Form->create($shopText); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                echo $this->Form->control('model');
                echo $this->Form->control('model_id', ['type' => 'text']);
                echo $this->Form->control('model_scope');
                echo $this->Form->control('locale');
                echo $this->Form->control('format');
                echo $this->Form->control('text', ['class' => 'htmleditor']);
                echo $this->Form->control('class');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop', 'Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>