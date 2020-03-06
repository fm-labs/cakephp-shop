<div class="ui form shop-text-form">
    <?= $this->Form->create($entity); ?>
    <?= $this->Form->control('text', ['type' => 'textarea', 'class' => 'htmleditor']); ?>
    <?= $this->Form->control('locale'); ?>
    <?= $this->Form->control('format'); ?>
    <?= $this->Form->hidden('class'); ?>
    <?= $this->Form->hidden('model'); ?>
    <?= $this->Form->hidden('model_id'); ?>
    <?= $this->Form->hidden('model_scope'); ?>
    <?= $this->Form->submit(); ?>
    <?= $this->Form->end(); ?>
</div>