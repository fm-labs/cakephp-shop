<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'billing'); ?>
<?php $this->assign('heading', __d('shop','Billing address')); ?>
<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Billing'), ['controller' => 'Checkout', 'action' => 'billing', 'ref' => 'breadcrumb']);
?>
<div class="shop checkout step billing">

    <?php if ($order->is_billing_selected && !$this->request->query('change')): ?>

        <div class="selected address">
            <?= $order->billing_first_name ?> <?= $order->billing_last_name ?><br />
            <?= $order->billing_street ?><br />
            <?= $order->billing_zipcode ?> <?= $order->billing_city ?><br />
            <?= $order->billing_country ?><br />


            <?php if (!$this->request->query('change')): ?>
            > <?= $this->Html->link(__d('shop', 'Change billing address'), ['action' => 'billing', 'change' => true], ['class' => '']); ?>
            <?php endif; ?>

        </div>
        <hr />
    <?php endif; ?>

    <?php if (!$order->is_billing_selected || $this->request->query('change')): ?>

    <?php if (!empty($billingAddresses)): ?>
        <h4><?= __d('shop', 'Stored addresses'); ?></h4>
        <?php foreach($billingAddresses as $address): ?>
            <div class="address">
                <?= $address->first_name ?> <?= $address->last_name ?>,
                <?= $address->street ?>,
                <?= $address->zipcode ?> <?= $address->city ?>,
                <?= $address->country ?><br />
                <?= $this->Html->link(__d('shop', 'Select address'), ['action' => 'billing', 'addressid' => $address->id], ['class' => 'btn btn-default']); ?>
            </div>
        <?php endforeach; ?>

        <hr />
    <?php endif; ?>

    <div class="form">
        <?= $this->Form->create($billingAddress, ['horizontal' => false, 'novalidate' => true]); ?>
        <?= $this->Form->input('first_name', ['label' => __d('shop','First Name')]); ?>
        <?= $this->Form->input('last_name', ['label' => __d('shop','Last Name')]); ?>
        <?= '' // $this->Form->input('name', ['label' => __d('shop','Name')]); ?>
        <?= $this->Form->input('street', ['label' => __d('shop','Street')]); ?>
        <?= '' //$this->Form->input('taxid', ['label' => __d('shop','Tax Id')]); ?>
        <?= $this->Form->input('zipcode', ['label' => __d('shop','Zipcode')]); ?>
        <?= $this->Form->input('city', ['label' => __d('shop','City')]); ?>
        <?= $this->Form->input('country', ['label' => __d('shop','Country')]); ?>


        <div class="actions" style="text-align: right; margin-top: 1em;">
            <?= $this->Form->submit(__d('shop','Continue'), ['class' => 'btn btn-primary']); ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
    <?php endif; ?>
</div>