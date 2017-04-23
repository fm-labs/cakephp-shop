<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'payment'); ?>
<?php $this->assign('heading', __d('shop','Pay with Credit Card')); ?>
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
<div class="form payment-method-form">
    <?= $this->Form->create($order); ?>
    <?= $this->Form->hidden('payment_type',['value' => 'credit_card_internal']); ?>
    <?= $this->Form->input('cc_brand',
        ['label' => __d('shop','Card Type'), 'options' => $ccBrands, 'empty' => __d('shop','Please select')]); ?>
    <?= $this->Form->input('cc_holder_name',
        ['label' => __d('shop','Card Holder'), 'placeholder' => __d('shop','Firstname Lastname')]); ?>
    <?= $this->Form->input('cc_number',
        ['label' => __d('shop','Card Number'), 'placeholder' => __d('shop','16 digits, no spaces')]); ?>
    <?= $this->Form->input('cc_expires_at',
        ['label' => __d('shop','Card valid until'), 'placeholder' => __d('shop','MM/YYYY')]); ?>
    <?= $this->Form->submit(); ?>
    <?= $this->Form->end(); ?>
</div>