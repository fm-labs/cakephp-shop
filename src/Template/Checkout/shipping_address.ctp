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
            <?= $this->Form->input('customer_address_id', ['label' => __d('shop','Saved addresses'), 'options' => $shippingAddresses]); ?>
            <?= $this->Form->button(__d('shop','Use this address')); ?>
        <?= $this->Form->end(); ?>
        <hr />
        <h3><?= __d('shop','Add new address'); ?></h3>
    <?php endif; ?>

    <div class="form">
        <?= $this->Form->create($shippingAddress, ['horizontal' => false, 'novalidate' => true]); ?>

        <?= $this->Form->hidden('_op', ['value' => 'shipping-customer-add']); ?>
        <div class="row">
            <div class="col-md-6">
                <?= $this->Form->input('first_name', ['label' => __d('shop','First Name')]); ?>
            </div>
            <div class="col-md-6">
                <?= $this->Form->input('last_name', ['label' => __d('shop','Last Name')]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $this->Form->input('street', ['label' => __d('shop','Street')]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?= $this->Form->input('zipcode', ['label' => __d('shop','Zipcode')]); ?>
            </div>
            <div class="col-md-9">
                <?= $this->Form->input('city', ['label' => __d('shop', 'City')]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $this->Form->input('country_id', ['label' => __d('shop','Country'), 'options' => $this->get('countries')]); ?>
            </div>
        </div>
        <div class="actions" style="text-align: right; margin-top: 1em;">
            <?= $this->Form->submit(__d('shop','Continue'), ['class' => 'btn btn-primary']); ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>

    <?php debug($shippingAddresses); ?>
</div>