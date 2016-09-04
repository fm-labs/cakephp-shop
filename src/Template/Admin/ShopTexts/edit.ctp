<?php $this->Html->addCrumb(__d('shop', 'Shop Texts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('shop', 'Edit {0}', __d('shop', 'Shop Text'))); ?>
<?= $this->Toolbar->addPostLink(
    __d('shop', 'Delete'),
    ['action' => 'delete', $shopText->id],
    ['icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopText->id)]
)
?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Texts')),
    ['action' => 'index'],
    ['icon' => 'list']
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
                echo $this->Form->input('model');
                echo $this->Form->input('model_id', ['type' => 'text']);
                echo $this->Form->input('model_scope');
                echo $this->Form->input('locale');
                echo $this->Form->input('format');
                echo $this->Form->input('text', ['class' => 'htmleditor']);
                echo $this->Form->input('class');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop', 'Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>