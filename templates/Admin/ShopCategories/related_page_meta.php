<div class="related page-metas">
    <?= $this->Form->create($pageMeta); ?>
    <div class="users ui basic segment">
        <div class="ui form">
            <?php
            echo $this->Form->hidden('model');
            echo $this->Form->hidden('foreignKey');
            echo $this->Form->control('title');
            echo $this->Form->control('description');
            echo $this->Form->control('keywords');
            echo $this->Form->control('robots');
            echo $this->Form->control('lang');
            ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop', 'Submit')) ?>
    </div>
</div>