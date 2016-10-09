<?php $this->Html->addCrumb(__d('shop','Shop Addresses'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($shopAddress->id); ?>
<?= $this->Toolbar->addLink(
    __d('shop','Edit {0}', __d('shop','Shop Address')),
    ['action' => 'edit', $shopAddress->id],
    ['data-icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','Delete {0}', __d('shop','Shop Address')),
    ['action' => 'delete', $shopAddress->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopAddress->id)]) ?>

<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Addresses')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Address')),
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
<?= $this->Toolbar->endGroup(); ?>
<div class="shopAddresses view">
    <h2 class="ui header">
        <?= h($shopAddress->id) ?>
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
            <td><?= $this->Number->format($shopAddress->id) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __d('shop','Is Company') ?></td>
            <td><?= $shopAddress->is_company ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>
        <tr class="boolean">
            <td><?= __d('shop','Is Archived') ?></td>
            <td><?= $shopAddress->is_archived ? __d('shop','Yes') : __d('shop','No'); ?></td>
        </tr>

        <tr>
            <td><?= __d('shop','Shop Customer') ?></td>
            <td><?= $shopAddress->has('shop_customer') ? $this->Html->link($shopAddress->shop_customer->id, ['controller' => 'ShopCustomers', 'action' => 'view', $shopAddress->shop_customer->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Type') ?></td>
            <td><?= h($shopAddress->type) ?></td>
        </tr>

        <tr>
            <td><?= __d('shop','First Name') ?></td>
            <td><?= h($shopAddress->first_name) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Last Name') ?></td>
            <td><?= h($shopAddress->last_name) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Taxid') ?></td>
            <td><?= h($shopAddress->taxid) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Street') ?></td>
            <td><?= h($shopAddress->street) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Street2') ?></td>
            <td><?= h($shopAddress->street2) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Zipcode') ?></td>
            <td><?= h($shopAddress->zipcode) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','City') ?></td>
            <td><?= h($shopAddress->city) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Country') ?></td>
            <td><?= h($shopAddress->country) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Country Iso2') ?></td>
            <td><?= h($shopAddress->country_iso2) ?></td>
        </tr>



        <tr class="date">
            <td><?= __d('shop','Created') ?></td>
            <td><?= h($shopAddress->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Modified') ?></td>
            <td><?= h($shopAddress->modified) ?></td>
        </tr>

    </table>
</div>
