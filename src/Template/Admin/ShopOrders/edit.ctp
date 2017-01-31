<?php $this->extend('Backend./Base/index'); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:admin:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop Orders'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Order {0}', $shopOrder->nr_formatted), ['action' => 'view', $shopOrder->id]); ?>
<?php $this->Breadcrumbs->add(__d('shop','Edit')); ?>
<?= $this->Toolbar->addPostLink(
    __d('shop','Delete'),
    ['action' => 'delete', $shopOrder->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopOrder->id)]
)
?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Orders')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Customers')),
    ['controller' => 'ShopCustomers', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Customer')),
    ['controller' => 'ShopCustomers', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Billing Addresses')),
    ['controller' => 'ShopAddresses', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Billing Address')),
    ['controller' => 'ShopAddresses', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Carts')),
    ['controller' => 'ShopCarts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Cart')),
    ['controller' => 'ShopCarts', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Order Items')),
    ['controller' => 'ShopOrderItems', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Order Item')),
    ['controller' => 'ShopOrderItems', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop','Edit {0} {1}', __d('shop','Shop Order'), $shopOrder->nr_formatted) ?>
    </h2>
    <?= $this->Form->create($shopOrder); ?>
    <?php

    echo $this->Form->hidden('uuid');
    echo $this->Form->hidden('cartid');
    echo $this->Form->hidden('sessionid');
    echo $this->Form->input('nr', ['readonly' => true]);
    echo $this->Form->input('title');
    ?>
    <div class="row">
        <div class="col-md-6">
            <h3>Billing</h3>
            <?php
            //echo $this->Form->input('billing_address_id', ['options' => $billingAddresses, 'empty' => true]);
            echo $this->Form->input('billing_is_company');
            echo $this->Form->input('billing_first_name');
            echo $this->Form->input('billing_last_name');
            echo $this->Form->input('billing_name');
            echo $this->Form->input('billing_street');
            echo $this->Form->input('billing_taxid');
            echo $this->Form->input('billing_zipcode');
            echo $this->Form->input('billing_city');
            echo $this->Form->input('billing_country');
            echo $this->Form->input('shipping_use_billing');
            ?>
        </div>
        <div class="col-md-6">
            <h3>Shipping</h3>
            <?php

            //echo $this->Form->input('shipping_address_id', ['options' => $shippingAddresses, 'empty' => true]);
            echo $this->Form->input('shipping_is_company');
            echo $this->Form->input('shipping_first_name');
            echo $this->Form->input('shipping_last_name');
            echo $this->Form->input('shipping_name');
            echo $this->Form->input('shipping_street');
            echo $this->Form->input('shipping_zipcode');
            echo $this->Form->input('shipping_city');
            echo $this->Form->input('shipping_country');

            echo $this->Form->input('shipping_type');
            ?>
        </div>
    </div>

    <fieldset>
        <legend>Payment</legend>
        <?php

        echo $this->Form->input('payment_type');
        echo $this->Form->input('payment_info_1');
        echo $this->Form->input('payment_info_2');
        echo $this->Form->input('payment_info_3');
        ?>
    </fieldset>

    <fieldset>
        <legend>Info</legend>
        <?php

        echo $this->Form->input('customer_notes');
        echo $this->Form->input('staff_notes');
        echo $this->Form->input('customer_phone');
        echo $this->Form->input('customer_email');
        echo $this->Form->input('customer_ip');
        echo $this->Form->input('agree_terms');
        echo $this->Form->input('agree_newsletter');
        echo $this->Form->input('locale');
        ?>
    </fieldset>

    <fieldset>
        <legend>Status</legend>
        <?php

        echo $this->Form->input('status');
        //echo $this->Form->input('submitted');
        //echo $this->Form->input('confirmed');
        //echo $this->Form->input('delivered');
        //echo $this->Form->input('invoiced');
        //echo $this->Form->input('payed');
        echo $this->Form->input('is_temporary');
        echo $this->Form->input('is_storno');
        echo $this->Form->input('is_deleted');
        ?>
    </fieldset>

    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

    <?php debug($shopOrder); ?>
    <?php debug($billingAddresses); ?>
</div>