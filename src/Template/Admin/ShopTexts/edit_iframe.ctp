<div class="form">
    <?php if ($shopText): ?>
    <h4>
        Model: <?= h($shopText->model); ?>[<?= h($shopText->model_id); ?>]:<?= h($shopText->model_scope); ?><br />
        Language: <?= h($shopText->locale); ?> |
        Format: <?= h($shopText->format); ?>
    </h4>
    <?php endif; ?>
    <?= $this->Form->create($shopText); ?>
    <div class="ui form">
    <?php
            echo $this->Form->hidden('model');
            echo $this->Form->hidden('model_id', ['type' => 'text']);
            echo $this->Form->hidden('model_scope');
            echo $this->Form->hidden('locale');
            echo $this->Form->hidden('format', ['default' => 'html']);
            echo $this->Form->input('text', ['class' => 'htmleditor']);
            echo $this->Form->hidden('class');
    ?>
    </div>
    <div class="ui basic right aligned segment">
        <?= $this->Form->button(__d('shop', 'Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>