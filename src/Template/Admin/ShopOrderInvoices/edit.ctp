<?php $this->Breadcrumbs->add(__d('shop', 'Shop Order Invoices'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Edit {0}', __d('shop', 'Shop Order Invoice'))); ?>
<?php $this->Toolbar->addPostLink(
    __d('shop', 'Delete'),
    ['action' => 'delete', $shopOrderInvoice->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopOrderInvoice->id)]
)
?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Order Invoices')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Parent Shop Order Invoices')),
    ['controller' => 'ShopOrderInvoices', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Parent Shop Order Invoice')),
    ['controller' => 'ShopOrderInvoices', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Orders')),
    ['controller' => 'ShopOrders', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Order')),
    ['controller' => 'ShopOrders', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop', 'Edit {0}', __d('shop', 'Shop Order Invoice')) ?>
    </h2>
    <?= $this->Form->create($shopOrderInvoice, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                    echo $this->Form->input('parent_id', ['options' => $parentShopOrderInvoices, 'empty' => true]);
                    echo $this->Form->input('shop_order_id', ['options' => $shopOrders]);
                echo $this->Form->input('group');
                echo $this->Form->input('nr');
                echo $this->Form->input('date_invoice');
                echo $this->Form->input('title');
                echo $this->Form->input('value_total');
                echo $this->Form->input('status');
                echo $this->Form->input('customer_notify_sent');
        ?>
        </div>

    <?= $this->Form->button(__d('shop', 'Submit')) ?>
    <?= $this->Form->end() ?>

</div>