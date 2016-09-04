<div class="related page-metas">
    <?= $this->Form->create($pageMeta); ?>
    <div class="users ui basic segment">
        <div class="ui form">
            <?php
            echo $this->Form->hidden('model');
            echo $this->Form->hidden('foreignKey');
            echo $this->Form->input('title');
            echo $this->Form->input('description');
            echo $this->Form->input('keywords');
            echo $this->Form->input('robots');
            echo $this->Form->input('lang');
            ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop', 'Submit')) ?>
    </div>
</div>