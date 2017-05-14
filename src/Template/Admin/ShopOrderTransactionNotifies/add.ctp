<?php $this->Breadcrumbs->add(__('Shop Order Transaction Notifies'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('New {0}', __('Shop Order Transaction Notify'))); ?>
<?php $this->Toolbar->addLink(
    __('List {0}', __('Shop Order Transaction Notifies')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->addLink(
    __('List {0}', __('Shop Order Transactions')),
    ['controller' => 'ShopOrderTransactions', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __('New {0}', __('Shop Order Transaction')),
    ['controller' => 'ShopOrderTransactions', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __('Add {0}', __('Shop Order Transaction Notify')) ?>
    </h2>
    <?= $this->Form->create($shopOrderTransactionNotify, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                    echo $this->Form->input('shop_order_transaction_id', ['options' => $shopOrderTransactions]);
                echo $this->Form->input('type');
                echo $this->Form->input('engine');
                echo $this->Form->input('request_ip');
                echo $this->Form->input('request_url');
                echo $this->Form->input('request_json');
                echo $this->Form->input('is_valid');
                echo $this->Form->input('is_processed');
        ?>
        </div>

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

</div>