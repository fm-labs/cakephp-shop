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
    <?= $this->Form->control('cc_brand',
        ['label' => __d('shop','Card Type'), 'options' => $ccBrands, 'empty' => __d('shop','Please select')]); ?>
    <?= $this->Form->control('cc_holder_name',
        ['label' => __d('shop','Card Holder'), 'placeholder' => __d('shop','Firstname Lastname')]); ?>
    <?= $this->Form->control('cc_number',
        ['label' => __d('shop','Card Number'), 'placeholder' => __d('shop','16 digits, no spaces')]); ?>
    <?= $this->Form->control('cc_expires_at',
        ['label' => __d('shop','Card valid until'), 'placeholder' => __d('shop','MM/YYYY')]); ?>

    <div class="row" style="margin-top: 30px;">
        <div class="col-sm-6">
            <?= $this->Html->link(__d('shop','Back to payment selection'),
                ['step' => 'payment', $order->cartid, 'change' => 1],
                ['class' => 'btn btn-default']); ?>
        </div>
        <div class="col-sm-6 text-right">
            <?= $this->Form->button(__d('shop','Continue'), ['class' => 'btn btn-primary']); ?>
        </div>
    </div>

    <?= $this->Form->end(); ?>
</div>