<?php $this->extend('Backend./Base/index'); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:admin:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop Orders'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Order {0}', $shopOrder->nr_formatted), ['action' => 'view', $shopOrder->id]); ?>
<?php $this->Breadcrumbs->add(__d('shop','Edit')); ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Customers')),
    ['controller' => 'ShopCustomers', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Customer')),
    ['controller' => 'ShopCustomers', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Billing Addresses')),
    ['controller' => 'ShopAddresses', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Billing Address')),
    ['controller' => 'ShopAddresses', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Carts')),
    ['controller' => 'ShopCarts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Cart')),
    ['controller' => 'ShopCarts', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Order Items')),
    ['controller' => 'ShopOrderItems', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
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
    echo $this->Form->control('nr', ['readonly' => true]);
    echo $this->Form->control('title');
    ?>
    <div class="row">
        <div class="col-md-6">
            <h3>Billing</h3>
            <?php
            //echo $this->Form->control('billing_address_id', ['options' => $billingAddresses, 'empty' => true]);
            echo $this->Form->control('billing_is_company');
            echo $this->Form->control('billing_first_name');
            echo $this->Form->control('billing_last_name');
            echo $this->Form->control('billing_name');
            echo $this->Form->control('billing_street');
            echo $this->Form->control('billing_taxid');
            echo $this->Form->control('billing_zipcode');
            echo $this->Form->control('billing_city');
            echo $this->Form->control('billing_country');
            echo $this->Form->control('shipping_use_billing');
            ?>
        </div>
        <div class="col-md-6">
            <h3>Shipping</h3>
            <?php

            //echo $this->Form->control('shipping_address_id', ['options' => $shippingAddresses, 'empty' => true]);
            echo $this->Form->control('shipping_is_company');
            echo $this->Form->control('shipping_first_name');
            echo $this->Form->control('shipping_last_name');
            echo $this->Form->control('shipping_name');
            echo $this->Form->control('shipping_street');
            echo $this->Form->control('shipping_zipcode');
            echo $this->Form->control('shipping_city');
            echo $this->Form->control('shipping_country');

            echo $this->Form->control('shipping_type');
            ?>
        </div>
    </div>
    <div class="clearfix"></div>

    <fieldset>
        <legend>Payment</legend>
        <?php

        echo $this->Form->control('payment_type');
        echo $this->Form->control('payment_info_1');
        echo $this->Form->control('payment_info_2');
        echo $this->Form->control('payment_info_3');
        ?>
    </fieldset>

    <fieldset>
        <legend>Info</legend>
        <?php

        echo $this->Form->control('customer_notes');
        echo $this->Form->control('staff_notes');
        echo $this->Form->control('customer_phone');
        echo $this->Form->control('customer_email');
        echo $this->Form->control('customer_ip');
        echo $this->Form->control('agree_terms');
        echo $this->Form->control('agree_newsletter');
        echo $this->Form->control('locale');
        ?>
    </fieldset>

    <fieldset>
        <legend>Status</legend>
        <?php

        echo $this->Form->control('status');
        //echo $this->Form->control('submitted');
        //echo $this->Form->control('confirmed');
        //echo $this->Form->control('delivered');
        //echo $this->Form->control('invoiced');
        //echo $this->Form->control('payed');
        echo $this->Form->control('is_temporary');
        echo $this->Form->control('is_storno');
        echo $this->Form->control('is_deleted');
        ?>
    </fieldset>

    <?= $this->Form->button(__d('shop','Submit')) ?>
    <?= $this->Form->end() ?>

    <?php debug($shopOrder); ?>
</div>