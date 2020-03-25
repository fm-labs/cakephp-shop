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
    <?php
    echo $this->Form->control('email');
    echo $this->Form->control('first_name');
    echo $this->Form->control('last_name');
    echo $this->Form->control('locale');
    echo $this->Form->control('is_guest');
    ?>
    <?= $this->Form->button(__d('shop','Submit')) ?>
    <?= $this->Form->end() ?>

</div>