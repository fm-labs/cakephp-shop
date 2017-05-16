<?php $this->Breadcrumbs->add(__('Shop Order Invoices'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('New {0}', __('Shop Order Invoice'))); ?>
<?php $this->Toolbar->addLink(
    __('List {0}', __('Shop Order Invoices')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->addLink(
    __('List {0}', __('Parent Shop Order Invoices')),
    ['controller' => 'ShopOrderInvoices', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __('New {0}', __('Parent Shop Order Invoice')),
    ['controller' => 'ShopOrderInvoices', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __('List {0}', __('Shop Orders')),
    ['controller' => 'ShopOrders', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __('New {0}', __('Shop Order')),
    ['controller' => 'ShopOrders', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Add {0}', __('Shop Order Invoice')) ?>
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

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

</div>