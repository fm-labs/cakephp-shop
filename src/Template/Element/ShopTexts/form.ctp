<?php
/*
use Cake\ORM\TableRegistry;

$ShopTexts = TableRegistry::get('Shop.ShopTexts');
$shopText = $ShopTexts->find()->where([
    'model' => $model,
    'model_id' => $modelId,
    'model_scope' => $modelScope,
    'locale' => (string) $locale
])
*/
if (!isset($shopText)) {
    echo "[No ShopText object set]";
    return;
}
?>
<div class="shop texts form">
    <?php echo $this->Form->create($shopText, [
        'id' => uniqid('shop_text_form'),
        'url' => [
            'plugin' => 'Shop', 'controller' => 'ShopTexts', 'action' => 'edit', $shopText->id,
            'redirect' => $this->Url->build()
        ]
    ]); ?>
    <?= $this->Form->hidden('model'); ?>
    <?= $this->Form->hidden('model_id'); ?>
    <?= $this->Form->hidden('model_scope'); ?>
    <?= $this->Form->hidden('locale'); ?>
    <?= $this->Form->input($shopText->model_scope, [
        'type' => 'textarea',
        'class' => 'htmleditor',
        'default' => $shopText->text
    ]); ?>
    <?= $this->Form->submit(); ?>
    <?= $this->Form->end(); ?>
</div>