<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'payment'); ?>
<?php $this->assign('heading', __d('shop','Payment')); ?><?php
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
            <?= $this->Form->submit(__d('shop','Continue'), ['class' => 'btn btn-primary']); ?>
        </div>

        <?= $this->Form->end(); ?>
    </div>

    <?php debug($paymentMethods); ?>
    <?php debug($paymentOptions); ?>
</div>