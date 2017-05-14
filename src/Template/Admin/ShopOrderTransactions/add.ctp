<?php $this->Breadcrumbs->add(__('Shop Order Transactions'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('New {0}', __('Shop Order Transaction'))); ?>
<?php $this->Toolbar->addLink(
    __('List {0}', __('Shop Order Transactions')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
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
        <?= __('Add {0}', __('Shop Order Transaction')) ?>
    </h2>
    <?= $this->Form->create($shopOrderTransaction, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                    echo $this->Form->input('shop_order_id', ['options' => $shopOrders]);
                echo $this->Form->input('type');
                echo $this->Form->input('engine');
                echo $this->Form->input('currency_code');
                echo $this->Form->input('value');
                echo $this->Form->input('status');
                echo $this->Form->input('ext_txnid');
                echo $this->Form->input('ext_status');
                echo $this->Form->input('init_response');
                echo $this->Form->input('init_request');
                echo $this->Form->input('redirect_url');
                echo $this->Form->input('custom1');
                echo $this->Form->input('custom2');
        ?>
        </div>

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

</div>