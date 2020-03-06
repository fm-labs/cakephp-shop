<?php $this->Breadcrumbs->add(__d('shop','Shop Addresses'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','New {0}', __d('shop','Shop Address'))); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Addresses')),
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
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop','Add {0}', __d('shop','Shop Address')) ?>
    </h2>
    <?= $this->Form->create($shopCustomerAddress); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                    echo $this->Form->control('shop_customer_id', ['options' => $shopCustomers, 'empty' => true]);
                echo $this->Form->control('type');
                echo $this->Form->control('refscope');
                echo $this->Form->control('refid');
                echo $this->Form->control('first_name');
                echo $this->Form->control('last_name');
                echo $this->Form->control('is_company');
                echo $this->Form->control('company_name');
                echo $this->Form->control('company_taxid');
                echo $this->Form->control('street1');
                echo $this->Form->control('street2');
                echo $this->Form->control('zipcode');
                echo $this->Form->control('city');
                echo $this->Form->control('country');
                echo $this->Form->control('country_iso2');
                echo $this->Form->control('phone');
                echo $this->Form->control('email');
                echo $this->Form->control('email_secondary');
                echo $this->Form->control('is_archived');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>