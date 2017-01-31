<?php
$ccBrands = [
    'visa' => 'Visa',
    'mastercard' => 'Mastercard',
    'dinersclub' => 'Diners Club'
];

$this->Form->context(new \Cake\View\Form\EntityContext($this->request, [
    'entity' => $order,
    'table' => 'Shop.ShopOrders'
]));
?>
<div class="payment-method" style="margin: -25px 0 0 10px;">
    <div class="payment-method-label">
        <span style="font-size: 1.2em;"><?= __d('shop','Credit card'); ?></span>
    </div>
    <div class="payment-method-select">
        <?= $this->Form->input('cc_brand',
            ['label' => __d('shop','Card Type'), 'options' => $ccBrands, 'empty' => __d('shop','Please select')]); ?>
        <?= $this->Form->input('cc_holder_name',
            ['label' => __d('shop','Card Holder'), 'placeholder' => __d('shop','Firstname Lastname')]); ?>
        <?= $this->Form->input('cc_number',
            ['label' => __d('shop','Card Number'), 'placeholder' => __d('shop','16 digits, no spaces')]); ?>
        <?= $this->Form->input('cc_expires_at',
            ['label' => __d('shop','Card valid until'), 'placeholder' => __d('shop','MM/YYYY')]); ?>
    </div>
</div>
<hr />