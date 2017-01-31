<?php $this->extend('Backend./Base/index'); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:admin:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop Orders'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($shopOrder->uuid); ?>
<?= $this->Toolbar->addLink(
    __d('shop','Edit {0}', __d('shop','Shop Order')),
    ['action' => 'edit', $shopOrder->id],
    ['data-icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','Delete {0}', __d('shop','Shop Order')),
    ['action' => 'delete', $shopOrder->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopOrder->id)]) ?>

<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Orders')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Order')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__d('shop','More')); ?>
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
<?= $this->Toolbar->endGroup(); ?>
<div class="shopOrders view">
    <h2 class="ui header">
        <?= h($shopOrder->title) ?>
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
            <td><?= __d('shop','Uuid') ?></td>
            <td><?= h($shopOrder->uuid) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Cartid') ?></td>
            <td><?= h($shopOrder->cartid) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Sessionid') ?></td>
            <td><?= h($shopOrder->sessionid) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shop Customer') ?></td>
            <td><?= $shopOrder->has('shop_customer') ? $this->Html->link($shopOrder->shop_customer->id, ['controller' => 'ShopCustomers', 'action' => 'view', $shopOrder->shop_customer->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Title') ?></td>
            <td><?= h($shopOrder->title) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shipping Type') ?></td>
            <td><?= h($shopOrder->shipping_type) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Status') ?></td>
            <td><?= h($shopOrder->status) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Billing Address') ?></td>
            <td><?= $shopOrder->has('billing_address') ? $this->Html->link($shopOrder->billing_address->oneline, ['controller' => 'ShopAddresses', 'action' => 'view', $shopOrder->billing_address->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Billing First Name') ?></td>
            <td><?= h($shopOrder->billing_first_name) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Billing Last Name') ?></td>
            <td><?= h($shopOrder->billing_last_name) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Billing Name') ?></td>
            <td><?= h($shopOrder->billing_name) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Billing Street') ?></td>
            <td><?= h($shopOrder->billing_street) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Billing Taxid') ?></td>
            <td><?= h($shopOrder->billing_taxid) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Billing Zipcode') ?></td>
            <td><?= h($shopOrder->billing_zipcode) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Billing City') ?></td>
            <td><?= h($shopOrder->billing_city) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Billing Country') ?></td>
            <td><?= h($shopOrder->billing_country) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shipping Address') ?></td>
            <td><?= $shopOrder->has('shipping_address') ? $this->Html->link($shopOrder->shipping_address->oneline, ['controller' => 'ShopAddresses', 'action' => 'view', $shopOrder->shipping_address->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shipping First Name') ?></td>
            <td><?= h($shopOrder->shipping_first_name) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shipping Last Name') ?></td>
            <td><?= h($shopOrder->shipping_last_name) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shipping Name') ?></td>
            <td><?= h($shopOrder->shipping_name) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shipping Street') ?></td>
            <td><?= h($shopOrder->shipping_street) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shipping Zipcode') ?></td>
            <td><?= h($shopOrder->shipping_zipcode) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shippping City') ?></td>
            <td><?= h($shopOrder->shipping_city) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shipping Country') ?></td>
            <td><?= h($shopOrder->shipping_country) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Customer Phone') ?></td>
            <td><?= h($shopOrder->customer_phone) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Customer Email') ?></td>
            <td><?= h($shopOrder->customer_email) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Customer Ip') ?></td>
            <td><?= h($shopOrder->customer_ip) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Payment Type') ?></td>
            <td><?= h($shopOrder->payment_type) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Payment Info 1') ?></td>
            <td><?= h($shopOrder->payment_info_1) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Payment Info 2') ?></td>
            <td><?= h($shopOrder->payment_info_2) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Payment Info 3') ?></td>
            <td><?= h($shopOrder->payment_info_3) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Locale') ?></td>
            <td><?= h($shopOrder->locale) ?></td>
        </tr>


        <tr>
            <td><?= __d('shop','Id') ?></td>
            <td><?= $this->Number->format($shopOrder->id) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Nr') ?></td>
            <td><?= $this->Number->format($shopOrder->nr) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Items Value Net') ?></td>
            <td><?= $this->Number->format($shopOrder->items_value_net) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Items Value Tax') ?></td>
            <td><?= $this->Number->format($shopOrder->items_value_tax) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Items Value Taxed') ?></td>
            <td><?= $this->Number->format($shopOrder->items_value_taxed) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shipping Value Net') ?></td>
            <td><?= $this->Number->format($shopOrder->shipping_value_net) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shipping Value Tax') ?></td>
            <td><?= $this->Number->format($shopOrder->shipping_value_tax) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Shipping Value Taxed') ?></td>
            <td><?= $this->Number->format($shopOrder->shipping_value_taxed) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Order Value Total') ?></td>
            <td><?= $this->Number->format($shopOrder->order_value_total) ?></td>
        </tr>


        <tr class="date">
            <td><?= __d('shop','Submitted') ?></td>
            <td><?= h($shopOrder->submitted) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Confirmed') ?></td>
            <td><?= h($shopOrder->confirmed) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Delivered') ?></td>
            <td><?= h($shopOrder->delivered) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Invoiced') ?></td>
            <td><?= h($shopOrder->invoiced) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Payed') ?></td>
            <td><?= h($shopOrder->payed) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Modified') ?></td>
            <td><?= h($shopOrder->modified) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Created') ?></td>
            <td><?= h($shopOrder->created) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __d('shop','Billing Is Company') ?></td>
            <td><?= $shopOrder->billing_is_company ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('shop','Shipping Use Billing') ?></td>
            <td><?= $shopOrder->shipping_use_billing ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('shop','Shipping Is Company') ?></td>
            <td><?= $shopOrder->shipping_is_company ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('shop','Is Temporary') ?></td>
            <td><?= $shopOrder->is_temporary ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('shop','Is Storno') ?></td>
            <td><?= $shopOrder->is_storno ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('shop','Is Deleted') ?></td>
            <td><?= $shopOrder->is_deleted ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('shop','Agree Terms') ?></td>
            <td><?= $shopOrder->agree_terms ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('shop','Agree Newsletter') ?></td>
            <td><?= $shopOrder->agree_newsletter ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>
        <tr class="text">
            <td><?= __d('shop','Customer Notes') ?></td>
            <td><?= $this->Text->autoParagraph(h($shopOrder->customer_notes)); ?></td>
        </tr>
        <tr class="text">
            <td><?= __d('shop','Staff Notes') ?></td>
            <td><?= $this->Text->autoParagraph(h($shopOrder->staff_notes)); ?></td>
        </tr>
    </table>
</div>
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __d('shop','Related {0}', __d('shop','ShopCarts')) ?></h4>
    <?php if (!empty($shopOrder->shop_carts)): ?>
    <table class="ui table">
        <tr>
            <th><?= __d('shop','Id') ?></th>
            <th><?= __d('shop','Sessionid') ?></th>
            <th><?= __d('shop','Userid') ?></th>
            <th><?= __d('shop','Refscope') ?></th>
            <th><?= __d('shop','Refid') ?></th>
            <th><?= __d('shop','Shop Order Id') ?></th>
            <th><?= __d('shop','Token') ?></th>
            <th><?= __d('shop','Items Value') ?></th>
            <th><?= __d('shop','Items Count') ?></th>
            <th><?= __d('shop','Created') ?></th>
            <th><?= __d('shop','Modified') ?></th>
            <th class="actions"><?= __d('shop','Actions') ?></th>
        </tr>
        <?php foreach ($shopOrder->shop_carts as $shopCarts): ?>
        <tr>
            <td><?= h($shopCarts->id) ?></td>
            <td><?= h($shopCarts->sessionid) ?></td>
            <td><?= h($shopCarts->userid) ?></td>
            <td><?= h($shopCarts->refscope) ?></td>
            <td><?= h($shopCarts->refid) ?></td>
            <td><?= h($shopCarts->shop_order_id) ?></td>
            <td><?= h($shopCarts->token) ?></td>
            <td><?= h($shopCarts->items_value) ?></td>
            <td><?= h($shopCarts->items_count) ?></td>
            <td><?= h($shopCarts->created) ?></td>
            <td><?= h($shopCarts->modified) ?></td>

            <td class="actions">
                <?= $this->Html->link(__d('shop','View'), ['controller' => 'ShopCarts', 'action' => 'view', $shopCarts->id]) ?>

                <?= $this->Html->link(__d('shop','Edit'), ['controller' => 'ShopCarts', 'action' => 'edit', $shopCarts->id]) ?>

                <?= $this->Form->postLink(__d('shop','Delete'), ['controller' => 'ShopCarts', 'action' => 'delete', $shopCarts->id], ['confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopCarts->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __d('shop','Related {0}', __d('shop','ShopOrderItems')) ?></h4>
    <?php if (!empty($shopOrder->shop_order_items)): ?>
    <table class="ui table">
        <tr>
            <th><?= __d('shop','Id') ?></th>
            <th><?= __d('shop','Shop Order Id') ?></th>
            <th><?= __d('shop','Refscope') ?></th>
            <th><?= __d('shop','Refid') ?></th>
            <th><?= __d('shop','Title') ?></th>
            <th><?= __d('shop','Amount') ?></th>
            <th><?= __d('shop','Unit') ?></th>
            <th><?= __d('shop','Item Value Net') ?></th>
            <th><?= __d('shop','Tax Rate') ?></th>
            <th><?= __d('shop','Value Net') ?></th>
            <th><?= __d('shop','Value Tax') ?></th>
            <th><?= __d('shop','Value Total') ?></th>
            <th><?= __d('shop','Options') ?></th>
            <th><?= __d('shop','Created') ?></th>
            <th><?= __d('shop','Modified') ?></th>
            <th class="actions"><?= __d('shop','Actions') ?></th>
        </tr>
        <?php foreach ($shopOrder->shop_order_items as $shopOrderItems): ?>
        <tr>
            <td><?= h($shopOrderItems->id) ?></td>
            <td><?= h($shopOrderItems->shop_order_id) ?></td>
            <td><?= h($shopOrderItems->refscope) ?></td>
            <td><?= h($shopOrderItems->refid) ?></td>
            <td><?= h($shopOrderItems->title) ?></td>
            <td><?= h($shopOrderItems->amount) ?></td>
            <td><?= h($shopOrderItems->unit) ?></td>
            <td><?= h($shopOrderItems->item_value_net) ?></td>
            <td><?= h($shopOrderItems->tax_rate) ?></td>
            <td><?= h($shopOrderItems->value_net) ?></td>
            <td><?= h($shopOrderItems->value_tax) ?></td>
            <td><?= h($shopOrderItems->value_total) ?></td>
            <td><?= h($shopOrderItems->options) ?></td>
            <td><?= h($shopOrderItems->created) ?></td>
            <td><?= h($shopOrderItems->modified) ?></td>

            <td class="actions">
                <?= $this->Html->link(__d('shop','View'), ['controller' => 'ShopOrderItems', 'action' => 'view', $shopOrderItems->id]) ?>

                <?= $this->Html->link(__d('shop','Edit'), ['controller' => 'ShopOrderItems', 'action' => 'edit', $shopOrderItems->id]) ?>

                <?= $this->Form->postLink(__d('shop','Delete'), ['controller' => 'ShopOrderItems', 'action' => 'delete', $shopOrderItems->id], ['confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopOrderItems->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
