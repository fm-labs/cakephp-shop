<?php $this->Breadcrumbs->add(__d('shop','Shop Customers'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Edit {0}', __d('shop','Shop Customer'))); ?>
<?php $this->Toolbar->addPostLink(
    __d('shop','Delete'),
    ['action' => 'delete', $shopCustomer->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopCustomer->id)]
)
?>
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
        <?= __d('shop','Edit {0}', __d('shop','Shop Customer')) ?>
    </h2>
    <?= $this->Form->create($shopCustomer); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                echo $this->Form->input('email');
                //echo $this->Form->input('password');
                echo $this->Form->input('greeting');
                echo $this->Form->input('first_name');
                echo $this->Form->input('last_name');
                echo $this->Form->input('street');
                echo $this->Form->input('zipcode');
                echo $this->Form->input('city');
                echo $this->Form->input('country');
                echo $this->Form->input('country_iso2');
                echo $this->Form->input('phone');
                echo $this->Form->input('fax');
                echo $this->Form->input('locale');
                echo $this->Form->input('email_verification_code');
                echo $this->Form->input('email_verified');
                echo $this->Form->input('is_guest');
                echo $this->Form->input('is_blocked');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>