<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'billing'); ?>
<?php $this->assign('heading', __d('shop','Billing address')); ?>
<?php
//$this->Breadcrumbs->add(__d('shop','Shop'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Billing'), ['controller' => 'Checkout', 'action' => 'billing', 'ref' => 'breadcrumb']);
?>
<div class="shop checkout step billing">

    <?php if (!empty($billingAddresses)): ?>
        <?= $this->Form->create(null); ?>
            <?= $this->Form->hidden('_op', ['value' => 'billing-customer-select']); ?>
            <?= $this->Form->input('customer_address_id', ['label' => __d('shop','Saved addresses'), 'options' => $billingAddresses]); ?>
            <div class="text-right">
                <?= $this->Form->button(__d('shop','Use this address'), ['class' => 'btn btn-lg btn-primary']); ?>
            </div>
        <?= $this->Form->end(); ?>
        <hr />
        <h3><?= __d('shop','New address'); ?></h3>
    <?php endif; ?>

    <?= $this->cell('Shop.AddressForm', [$billingAddress, ['submit' => __d('shop','Continue')]]); ?>

    <!--
    <div class="form">
        <?= $this->Form->create($billingAddress, ['horizontal' => false, 'novalidate' => true]); ?>

        <?= $this->Form->hidden('_op', ['value' => 'billing-customer-add']); ?>
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
    -->

    <?php debug($billingAddresses); ?>
</div>