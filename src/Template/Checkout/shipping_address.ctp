<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'shipping_address'); ?>
<?php $this->assign('heading', __d('shop','Shipping address')); ?>
<?php
//$this->Breadcrumbs->add(__d('shop','Shop'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Billing'), ['controller' => 'Checkout', 'action' => 'shipping', 'ref' => 'breadcrumb']);
?>
<div class="shop checkout step shipping">

    <?php if (!empty($shippingAddresses)): ?>
        <?= $this->Form->create(null); ?>
            <?= $this->Form->hidden('_op', ['value' => 'shipping-customer-select']); ?>
            <?= $this->Form->control('customer_address_id', ['label' => __d('shop','Saved addresses'), 'options' => $shippingAddresses]); ?>
            <div class="text-right">
                <?= $this->Form->button(__d('shop','Use this address'), ['class' => 'btn btn-primary']); ?>
            </div>
        <?= $this->Form->end(); ?>
        <hr />
        <h3><?= __d('shop','Add new address'); ?></h3>
    <?php endif; ?>

    <?= $this->cell('Shop.AddressForm', [$shippingAddress, ['submit' => __d('shop','Continue')]]); ?>
    <?php debug($shippingAddresses); ?>
</div>