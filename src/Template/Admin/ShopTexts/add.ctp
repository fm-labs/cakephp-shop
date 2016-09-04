<?php $this->Html->addCrumb(__d('shop', 'Shop Texts'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('shop', 'New {0}', __d('shop', 'Shop Text'))); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Texts')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop', 'Add {0}', __d('shop', 'Shop Text')) ?>
    </h2>
    <?= $this->Form->create($shopText); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('model');
                echo $this->Form->input('model_id');
                echo $this->Form->input('model_scope');
                echo $this->Form->input('locale');
                echo $this->Form->input('format');
                echo $this->Form->input('text');
                echo $this->Form->input('class');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop', 'Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>