<?php $this->Breadcrumbs->add(__d('shop','Shop Customers'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','New {0}', __d('shop','Shop Customer'))); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Customers')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Addresses')),
    ['controller' => 'ShopAddresses', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Address')),
    ['controller' => 'ShopAddresses', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Orders')),
    ['controller' => 'ShopOrders', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Order')),
    ['controller' => 'ShopOrders', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop','Add {0}', __d('shop','Shop Customer')) ?>
    </h2>
    <?= $this->Form->create($shopCustomer); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                echo $this->Form->control('email');
                //echo $this->Form->control('password');
                echo $this->Form->control('greeting');
                echo $this->Form->control('first_name');
                echo $this->Form->control('last_name');
                echo $this->Form->control('street');
                echo $this->Form->control('zipcode');
                echo $this->Form->control('city');
                echo $this->Form->control('country');
                echo $this->Form->control('country_iso2');
                echo $this->Form->control('phone');
                echo $this->Form->control('fax');
                echo $this->Form->control('locale');
                echo $this->Form->control('email_verification_code');
                echo $this->Form->control('email_verified');
                echo $this->Form->control('is_guest');
                echo $this->Form->control('is_blocked');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>