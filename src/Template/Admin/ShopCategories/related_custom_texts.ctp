<div class="shop categories custom-texts">
    <div class="ui form">
        <?= $this->Form->create($shopCategory); ?>
        <?= $this->Form->input('custom_text1', [
            'type' => 'htmleditor',
            'label' => 'Related ll',
            'editor' => [
                'relative_urls' => false,
                'remove_script_host' => false,
                'convert_urls' => false,
            ]
        ]); ?>
        <?= $this->Form->input('custom_text2', [
            'type' => 'htmleditor',
            'label' => 'Related stone',
            'editor' => [
                'relative_urls' => false,
                'remove_script_host' => false,
                'convert_urls' => false,
            ]
        ]); ?>
        <?= ''//$this->Form->input('custom_text3', ['type' => 'htmleditor']); ?>
        <?= ''//$this->Form->input('custom_text4', ['type' => 'htmleditor']); ?>
        <?= ''//$this->Form->input('custom_text5', ['type' => 'htmleditor']); ?>
        <?= $this->Form->button(__d('shop', 'Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>