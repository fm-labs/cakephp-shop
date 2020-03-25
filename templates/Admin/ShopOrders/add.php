<?php $this->extend('Backend./Base/index'); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:admin:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop Orders'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','New {0}', __d('shop','Shop Order'))); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Orders')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
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
        <?= __d('shop','Add {0}', __d('shop','Shop Order')) ?>
    </h2>
    <?= $this->Form->create($shopOrder); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                echo $this->Form->control('uuid');
                echo $this->Form->control('cartid');
                echo $this->Form->control('sessionid');
                    echo $this->Form->control('shop_customer_id', ['options' => $shopCustomers, 'empty' => true]);
                echo $this->Form->control('nr');
                echo $this->Form->control('title');
                echo $this->Form->control('items_value_net');
                echo $this->Form->control('items_value_tax');
                echo $this->Form->control('items_value_taxed');
                echo $this->Form->control('shipping_type');
                echo $this->Form->control('shipping_value_net');
                echo $this->Form->control('shipping_value_tax');
                echo $this->Form->control('shipping_value_taxed');
                echo $this->Form->control('order_value_total');
                echo $this->Form->control('status');
                //echo $this->Form->control('submitted');
                //echo $this->Form->control('confirmed');
                //echo $this->Form->control('delivered');
                //echo $this->Form->control('invoiced');
                //echo $this->Form->control('payed');
                echo $this->Form->control('customer_notes');
                echo $this->Form->control('staff_notes');
                    echo $this->Form->control('billing_address_id', ['options' => $billingAddresses, 'empty' => true]);
                echo $this->Form->control('billing_first_name');
                echo $this->Form->control('billing_last_name');
                echo $this->Form->control('billing_name');
                echo $this->Form->control('billing_is_company');
                echo $this->Form->control('billing_street');
                echo $this->Form->control('billing_taxid');
                echo $this->Form->control('billing_zipcode');
                echo $this->Form->control('billing_city');
                echo $this->Form->control('billing_country');
                    echo $this->Form->control('shipping_address_id', ['options' => $shippingAddresses, 'empty' => true]);
                echo $this->Form->control('shipping_use_billing');
                echo $this->Form->control('shipping_first_name');
                echo $this->Form->control('shipping_last_name');
                echo $this->Form->control('shipping_name');
                echo $this->Form->control('shipping_is_company');
                echo $this->Form->control('shipping_street');
                echo $this->Form->control('shipping_zipcode');
                echo $this->Form->control('shipping_city');
                echo $this->Form->control('shipping_country');
                echo $this->Form->control('customer_phone');
                echo $this->Form->control('customer_email');
                echo $this->Form->control('customer_ip');
                echo $this->Form->control('payment_type');
                echo $this->Form->control('payment_info_1');
                echo $this->Form->control('payment_info_2');
                echo $this->Form->control('payment_info_3');
                echo $this->Form->control('is_temporary');
                echo $this->Form->control('is_storno');
                echo $this->Form->control('is_deleted');
                echo $this->Form->control('agree_terms');
                echo $this->Form->control('agree_newsletter');
                echo $this->Form->control('locale');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>