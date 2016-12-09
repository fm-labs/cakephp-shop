<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'payment'); ?>
<?php $this->assign('heading', __d('shop','Payment')); ?><?php
$this->Breadcrumbs->add(__('Shop'), ['_name' => 'shop:index']);
$this->Breadcrumbs->add(__('Checkout'), ['controller' => 'Checkout', 'action' => 'index']);
$this->Breadcrumbs->add(__('Payment'), ['controller' => 'Checkout', 'action' => 'payment']);
?>
<div class="shop checkout step payment">

    <?php
    $_this =& $this;
    array_walk($paymentOptions, function (&$val, $idx) use ($_this) {

        $element = 'Shop.Checkout/Payment/' . $idx;
        if ($_this->elementExists($element)) {
            $val = $_this->element($element);
        }
    });
    ?>

    <h2><?= __d('shop', 'Select Payment Method'); ?></h2>
    <div class="ui form">
        <?= $this->Form->create($order); ?>
        <?= $this->Form->input('payment_type', [
            'type' => 'radio',
            'options' => $paymentOptions,
            'label' => false,
            'escape' => false,
            'class' => 'wide'
        ]); ?>

        <div class="ui divider"></div>
        <div class="actions" style="text-align: right;">
            <?= $this->Form->submit(__d('shop','Continue'), ['class' => 'ui primary button']); ?>
        </div>

        <?= $this->Form->end(); ?>
    </div>

</div>