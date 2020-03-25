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
            <?= $this->Form->control('customer_address_id', ['label' => __d('shop','Saved addresses'), 'options' => $billingAddresses]); ?>
            <div class="text-right">
                <?= $this->Form->button(__d('shop','Use this address'), ['class' => 'btn btn-primary']); ?>
            </div>
        <?= $this->Form->end(); ?>
        <hr />
        <h3><?= __d('shop','New address'); ?></h3>
    <?php endif; ?>

    <?= $this->cell('Shop.AddressForm', [$billingAddress, ['submit' => __d('shop','Continue')]]); ?>
    <?php debug($billingAddresses); ?>
</div>