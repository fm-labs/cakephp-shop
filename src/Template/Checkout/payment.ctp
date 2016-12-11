<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'payment'); ?>
<?php $this->assign('heading', __d('shop','Select payment method')); ?>
<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Payment'), ['controller' => 'Checkout', 'action' => 'payment', 'ref' => 'breadcrumb']);
?>
<div class="shop checkout step payment">

    <?php

    $_this =& $this;
    array_walk($paymentOptions, function (&$val, $idx) use ($_this, $order) {

        $element = 'Shop.Checkout/Payment/' . $idx . '_form';
        if ($_this->elementExists($element)) {
            $val = $_this->element($element);
        }
    });

    ?>
    <div class="form">
        <?= $this->Form->create($order, ['url' => ['action' => 'step', 'step' => 'payment', 'change_type' => true]]); ?>
        <?= $this->Form->input('payment_type', [
            'type' => 'radio',
            'options' => $paymentOptions,
            'label' => false,
            'escape' => false,
            'class' => 'wide'
        ]); ?>

        <hr/>
        <div class="actions text-right">
            <?= $this->Form->button(__d('shop','Continue'), ['class' => 'btn btn-primary']); ?>
        </div>

        <?= $this->Form->end(); ?>
    </div>

    <?php debug($paymentMethods); ?>
    <?php debug($paymentOptions); ?>
</div>