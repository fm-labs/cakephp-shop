<?php $this->Breadcrumbs->add(__d('shop','Shop Addresses'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($shopCustomerAddress->id); ?>
<?php $this->Toolbar->addLink(
    __d('shop','Edit {0}', __d('shop','Shop Address')),
    ['action' => 'edit', $shopCustomerAddress->id],
    ['data-icon' => 'edit']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','Delete {0}', __d('shop','Shop Address')),
    ['action' => 'delete', $shopCustomerAddress->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopCustomerAddress->id)]) ?>

<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Addresses')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Address')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->startGroup(__d('shop','More')); ?>
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
<?php $this->Toolbar->endGroup(); ?>
<div class="shopCustomerAddresses view">
    <h2 class="ui header">
        <?= h($shopCustomerAddress->id) ?>
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
            <td><?= __d('shop','Id') ?></td>
            <td><?= $this->Number->format($shopCustomerAddress->id) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __d('shop','Is Company') ?></td>
            <td><?= $shopCustomerAddress->is_company ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('shop','Is Archived') ?></td>
            <td><?= $shopCustomerAddress->is_archived ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>

        <tr>
            <td><?= __d('shop','Shop Customer') ?></td>
            <td><?= $shopCustomerAddress->has('shop_customer') ? $this->Html->link($shopCustomerAddress->shop_customer->id, ['controller' => 'ShopCustomers', 'action' => 'view', $shopCustomerAddress->shop_customer->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Type') ?></td>
            <td><?= h($shopCustomerAddress->type) ?></td>
        </tr>

        <tr>
            <td><?= __d('shop','First Name') ?></td>
            <td><?= h($shopCustomerAddress->first_name) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Last Name') ?></td>
            <td><?= h($shopCustomerAddress->last_name) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Taxid') ?></td>
            <td><?= h($shopCustomerAddress->taxid) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Street') ?></td>
            <td><?= h($shopCustomerAddress->street) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Street2') ?></td>
            <td><?= h($shopCustomerAddress->street2) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Zipcode') ?></td>
            <td><?= h($shopCustomerAddress->zipcode) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','City') ?></td>
            <td><?= h($shopCustomerAddress->city) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Country') ?></td>
            <td><?= h($shopCustomerAddress->country) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Country Iso2') ?></td>
            <td><?= h($shopCustomerAddress->country_iso2) ?></td>
        </tr>



        <tr class="date">
            <td><?= __d('shop','Created') ?></td>
            <td><?= h($shopCustomerAddress->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Modified') ?></td>
            <td><?= h($shopCustomerAddress->modified) ?></td>
        </tr>

    </table>
</div>
