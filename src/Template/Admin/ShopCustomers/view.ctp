<?php $this->Html->addCrumb(__d('shop','Shop Customers'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($shopCustomer->display_name); ?>
<?= $this->Toolbar->addLink(
    __d('shop','Edit {0}', __d('shop','Shop Customer')),
    ['action' => 'edit', $shopCustomer->id],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','Delete {0}', __d('shop','Shop Customer')),
    ['action' => 'delete', $shopCustomer->id],
    ['icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopCustomer->id)]) ?>

<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Customers')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Customer')),
    ['action' => 'add'],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__d('shop','More')); ?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Addresses')),
    ['controller' => 'ShopAddresses', 'action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Address')),
    ['controller' => 'ShopAddresses', 'action' => 'add'],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Orders')),
    ['controller' => 'ShopOrders', 'action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Order')),
    ['controller' => 'ShopOrders', 'action' => 'add'],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->endGroup(); ?>
<div class="shopCustomers view">
    <h2 class="ui header">
        <?= h($shopCustomer->display_name) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __d('shop','Label'); ?></th>
            <th><?= __d('shop','Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __d('shop','Email') ?></td>
            <td><?= h($shopCustomer->email) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Password') ?></td>
            <td><?= h($shopCustomer->password) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Greeting') ?></td>
            <td><?= h($shopCustomer->greeting) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','First Name') ?></td>
            <td><?= h($shopCustomer->first_name) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Last Name') ?></td>
            <td><?= h($shopCustomer->last_name) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Street') ?></td>
            <td><?= h($shopCustomer->street) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Zipcode') ?></td>
            <td><?= h($shopCustomer->zipcode) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','City') ?></td>
            <td><?= h($shopCustomer->city) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Country') ?></td>
            <td><?= h($shopCustomer->country) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Country Iso2') ?></td>
            <td><?= h($shopCustomer->country_iso2) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Phone') ?></td>
            <td><?= h($shopCustomer->phone) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Fax') ?></td>
            <td><?= h($shopCustomer->fax) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Locale') ?></td>
            <td><?= h($shopCustomer->locale) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Email Verification Code') ?></td>
            <td><?= h($shopCustomer->email_verification_code) ?></td>
        </tr>


        <tr>
            <td><?= __d('shop','Id') ?></td>
            <td><?= $this->Number->format($shopCustomer->id) ?></td>
        </tr>


        <tr class="date">
            <td><?= __d('shop','Created') ?></td>
            <td><?= h($shopCustomer->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Modified') ?></td>
            <td><?= h($shopCustomer->modified) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __d('shop','Email Verified') ?></td>
            <td><?= $shopCustomer->email_verified ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('shop','Is Guest') ?></td>
            <td><?= $shopCustomer->is_guest ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('shop','Is Blocked') ?></td>
            <td><?= $shopCustomer->is_blocked ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>
    </table>
</div>
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __d('shop','Related {0}', __d('shop','ShopAddresses')) ?></h4>
    <?php if (!empty($shopCustomer->shop_addresses)): ?>
    <table class="ui table">
        <tr>
            <th><?= __d('shop','Id') ?></th>
            <th><?= __d('shop','Shop Customer Id') ?></th>
            <th><?= __d('shop','Type') ?></th>
            <th><?= __d('shop','Refscope') ?></th>
            <th><?= __d('shop','Refid') ?></th>
            <th><?= __d('shop','First Name') ?></th>
            <th><?= __d('shop','Last Name') ?></th>
            <th><?= __d('shop','Is Company') ?></th>
            <th><?= __d('shop','Company Name') ?></th>
            <th><?= __d('shop','Company Taxid') ?></th>
            <th><?= __d('shop','Street1') ?></th>
            <th><?= __d('shop','Street2') ?></th>
            <th><?= __d('shop','Zipcode') ?></th>
            <th><?= __d('shop','City') ?></th>
            <th><?= __d('shop','Country') ?></th>
            <th><?= __d('shop','Country Iso2') ?></th>
            <th><?= __d('shop','Phone') ?></th>
            <th><?= __d('shop','Email') ?></th>
            <th><?= __d('shop','Email Secondary') ?></th>
            <th><?= __d('shop','Is Archived') ?></th>
            <th><?= __d('shop','Created') ?></th>
            <th><?= __d('shop','Modified') ?></th>
            <th class="actions"><?= __d('shop','Actions') ?></th>
        </tr>
        <?php foreach ($shopCustomer->shop_addresses as $shopAddresses): ?>
        <tr>
            <td><?= h($shopAddresses->id) ?></td>
            <td><?= h($shopAddresses->shop_customer_id) ?></td>
            <td><?= h($shopAddresses->type) ?></td>
            <td><?= h($shopAddresses->refscope) ?></td>
            <td><?= h($shopAddresses->refid) ?></td>
            <td><?= h($shopAddresses->first_name) ?></td>
            <td><?= h($shopAddresses->last_name) ?></td>
            <td><?= h($shopAddresses->is_company) ?></td>
            <td><?= h($shopAddresses->company_name) ?></td>
            <td><?= h($shopAddresses->company_taxid) ?></td>
            <td><?= h($shopAddresses->street1) ?></td>
            <td><?= h($shopAddresses->street2) ?></td>
            <td><?= h($shopAddresses->zipcode) ?></td>
            <td><?= h($shopAddresses->city) ?></td>
            <td><?= h($shopAddresses->country) ?></td>
            <td><?= h($shopAddresses->country_iso2) ?></td>
            <td><?= h($shopAddresses->phone) ?></td>
            <td><?= h($shopAddresses->email) ?></td>
            <td><?= h($shopAddresses->email_secondary) ?></td>
            <td><?= h($shopAddresses->is_archived) ?></td>
            <td><?= h($shopAddresses->created) ?></td>
            <td><?= h($shopAddresses->modified) ?></td>

            <td class="actions">
                <?= $this->Html->link(__d('shop','View'), ['controller' => 'ShopAddresses', 'action' => 'view', $shopAddresses->id]) ?>

                <?= $this->Html->link(__d('shop','Edit'), ['controller' => 'ShopAddresses', 'action' => 'edit', $shopAddresses->id]) ?>

                <?= $this->Form->postLink(__d('shop','Delete'), ['controller' => 'ShopAddresses', 'action' => 'delete', $shopAddresses->id], ['confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopAddresses->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __d('shop','Related {0}', __d('shop','ShopOrders')) ?></h4>
    <?php if (!empty($shopCustomer->shop_orders)): ?>
    <table class="ui table">
        <tr>
            <th><?= __d('shop','Id') ?></th>
            <th><?= __d('shop','Uuid') ?></th>
            <th><?= __d('shop','Cartid') ?></th>
            <th><?= __d('shop','Sessionid') ?></th>
            <th><?= __d('shop','Shop Customer Id') ?></th>
            <th><?= __d('shop','Nr') ?></th>
            <th><?= __d('shop','Title') ?></th>
            <th><?= __d('shop','Items Value Net') ?></th>
            <th><?= __d('shop','Items Value Tax') ?></th>
            <th><?= __d('shop','Items Value Taxed') ?></th>
            <th><?= __d('shop','Shipping Type') ?></th>
            <th><?= __d('shop','Shipping Value Net') ?></th>
            <th><?= __d('shop','Shipping Value Tax') ?></th>
            <th><?= __d('shop','Shipping Value Taxed') ?></th>
            <th><?= __d('shop','Order Value Total') ?></th>
            <th><?= __d('shop','Status') ?></th>
            <th><?= __d('shop','Submitted') ?></th>
            <th><?= __d('shop','Confirmed') ?></th>
            <th><?= __d('shop','Delivered') ?></th>
            <th><?= __d('shop','Invoiced') ?></th>
            <th><?= __d('shop','Payed') ?></th>
            <th><?= __d('shop','Customer Notes') ?></th>
            <th><?= __d('shop','Staff Notes') ?></th>
            <th><?= __d('shop','Billing Address Id') ?></th>
            <th><?= __d('shop','Billing First Name') ?></th>
            <th><?= __d('shop','Billing Last Name') ?></th>
            <th><?= __d('shop','Billing Name') ?></th>
            <th><?= __d('shop','Billing Is Company') ?></th>
            <th><?= __d('shop','Billing Street') ?></th>
            <th><?= __d('shop','Billing Taxid') ?></th>
            <th><?= __d('shop','Billing Zipcode') ?></th>
            <th><?= __d('shop','Billing City') ?></th>
            <th><?= __d('shop','Billing Country') ?></th>
            <th><?= __d('shop','Shipping Address Id') ?></th>
            <th><?= __d('shop','Shipping Use Billing') ?></th>
            <th><?= __d('shop','Shipping First Name') ?></th>
            <th><?= __d('shop','Shipping Last Name') ?></th>
            <th><?= __d('shop','Shipping Name') ?></th>
            <th><?= __d('shop','Shipping Is Company') ?></th>
            <th><?= __d('shop','Shipping Street') ?></th>
            <th><?= __d('shop','Shipping Zipcode') ?></th>
            <th><?= __d('shop','Shippping City') ?></th>
            <th><?= __d('shop','Shipping Country') ?></th>
            <th><?= __d('shop','Customer Phone') ?></th>
            <th><?= __d('shop','Customer Email') ?></th>
            <th><?= __d('shop','Customer Ip') ?></th>
            <th><?= __d('shop','Payment Type') ?></th>
            <th><?= __d('shop','Payment Info 1') ?></th>
            <th><?= __d('shop','Payment Info 2') ?></th>
            <th><?= __d('shop','Payment Info 3') ?></th>
            <th><?= __d('shop','Is Temporary') ?></th>
            <th><?= __d('shop','Is Storno') ?></th>
            <th><?= __d('shop','Is Deleted') ?></th>
            <th><?= __d('shop','Agree Terms') ?></th>
            <th><?= __d('shop','Agree Newsletter') ?></th>
            <th><?= __d('shop','Locale') ?></th>
            <th><?= __d('shop','Modified') ?></th>
            <th><?= __d('shop','Created') ?></th>
            <th class="actions"><?= __d('shop','Actions') ?></th>
        </tr>
        <?php foreach ($shopCustomer->shop_orders as $shopOrders): ?>
        <tr>
            <td><?= h($shopOrders->id) ?></td>
            <td><?= h($shopOrders->uuid) ?></td>
            <td><?= h($shopOrders->cartid) ?></td>
            <td><?= h($shopOrders->sessionid) ?></td>
            <td><?= h($shopOrders->shop_customer_id) ?></td>
            <td><?= h($shopOrders->nr) ?></td>
            <td><?= h($shopOrders->title) ?></td>
            <td><?= h($shopOrders->items_value_net) ?></td>
            <td><?= h($shopOrders->items_value_tax) ?></td>
            <td><?= h($shopOrders->items_value_taxed) ?></td>
            <td><?= h($shopOrders->shipping_type) ?></td>
            <td><?= h($shopOrders->shipping_value_net) ?></td>
            <td><?= h($shopOrders->shipping_value_tax) ?></td>
            <td><?= h($shopOrders->shipping_value_taxed) ?></td>
            <td><?= h($shopOrders->order_value_total) ?></td>
            <td><?= h($shopOrders->status) ?></td>
            <td><?= h($shopOrders->submitted) ?></td>
            <td><?= h($shopOrders->confirmed) ?></td>
            <td><?= h($shopOrders->delivered) ?></td>
            <td><?= h($shopOrders->invoiced) ?></td>
            <td><?= h($shopOrders->payed) ?></td>
            <td><?= h($shopOrders->customer_notes) ?></td>
            <td><?= h($shopOrders->staff_notes) ?></td>
            <td><?= h($shopOrders->billing_address_id) ?></td>
            <td><?= h($shopOrders->billing_first_name) ?></td>
            <td><?= h($shopOrders->billing_last_name) ?></td>
            <td><?= h($shopOrders->billing_name) ?></td>
            <td><?= h($shopOrders->billing_is_company) ?></td>
            <td><?= h($shopOrders->billing_street) ?></td>
            <td><?= h($shopOrders->billing_taxid) ?></td>
            <td><?= h($shopOrders->billing_zipcode) ?></td>
            <td><?= h($shopOrders->billing_city) ?></td>
            <td><?= h($shopOrders->billing_country) ?></td>
            <td><?= h($shopOrders->shipping_address_id) ?></td>
            <td><?= h($shopOrders->shipping_use_billing) ?></td>
            <td><?= h($shopOrders->shipping_first_name) ?></td>
            <td><?= h($shopOrders->shipping_last_name) ?></td>
            <td><?= h($shopOrders->shipping_name) ?></td>
            <td><?= h($shopOrders->shipping_is_company) ?></td>
            <td><?= h($shopOrders->shipping_street) ?></td>
            <td><?= h($shopOrders->shipping_zipcode) ?></td>
            <td><?= h($shopOrders->shipping_city) ?></td>
            <td><?= h($shopOrders->shipping_country) ?></td>
            <td><?= h($shopOrders->customer_phone) ?></td>
            <td><?= h($shopOrders->customer_email) ?></td>
            <td><?= h($shopOrders->customer_ip) ?></td>
            <td><?= h($shopOrders->payment_type) ?></td>
            <td><?= h($shopOrders->payment_info_1) ?></td>
            <td><?= h($shopOrders->payment_info_2) ?></td>
            <td><?= h($shopOrders->payment_info_3) ?></td>
            <td><?= h($shopOrders->is_temporary) ?></td>
            <td><?= h($shopOrders->is_storno) ?></td>
            <td><?= h($shopOrders->is_deleted) ?></td>
            <td><?= h($shopOrders->agree_terms) ?></td>
            <td><?= h($shopOrders->agree_newsletter) ?></td>
            <td><?= h($shopOrders->locale) ?></td>
            <td><?= h($shopOrders->modified) ?></td>
            <td><?= h($shopOrders->created) ?></td>

            <td class="actions">
                <?= $this->Html->link(__d('shop','View'), ['controller' => 'ShopOrders', 'action' => 'view', $shopOrders->id]) ?>

                <?= $this->Html->link(__d('shop','Edit'), ['controller' => 'ShopOrders', 'action' => 'edit', $shopOrders->id]) ?>

                <?= $this->Form->postLink(__d('shop','Delete'), ['controller' => 'ShopOrders', 'action' => 'delete', $shopOrders->id], ['confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopOrders->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
