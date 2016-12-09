<?php $this->Breadcrumbs->add(__d('shop','Shop Addresses'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','New {0}', __d('shop','Shop Address'))); ?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Addresses')),
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
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop','Add {0}', __d('shop','Shop Address')) ?>
    </h2>
    <?= $this->Form->create($shopAddress); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                    echo $this->Form->input('shop_customer_id', ['options' => $shopCustomers, 'empty' => true]);
                echo $this->Form->input('type');
                echo $this->Form->input('refscope');
                echo $this->Form->input('refid');
                echo $this->Form->input('first_name');
                echo $this->Form->input('last_name');
                echo $this->Form->input('is_company');
                echo $this->Form->input('company_name');
                echo $this->Form->input('company_taxid');
                echo $this->Form->input('street1');
                echo $this->Form->input('street2');
                echo $this->Form->input('zipcode');
                echo $this->Form->input('city');
                echo $this->Form->input('country');
                echo $this->Form->input('country_iso2');
                echo $this->Form->input('phone');
                echo $this->Form->input('email');
                echo $this->Form->input('email_secondary');
                echo $this->Form->input('is_archived');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>