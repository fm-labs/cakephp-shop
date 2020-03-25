<?php $this->Breadcrumbs->add(__d('shop', 'Shop Order Transaction Notifies'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Edit {0}', __d('shop', 'Shop Order Transaction Notify'))); ?>
<?php $this->Toolbar->addPostLink(
    __d('shop', 'Delete'),
    ['action' => 'delete', $shopOrderTransactionNotify->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopOrderTransactionNotify->id)]
)
?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Order Transaction Notifies')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Order Transactions')),
    ['controller' => 'ShopOrderTransactions', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Order Transaction')),
    ['controller' => 'ShopOrderTransactions', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop', 'Edit {0}', __d('shop', 'Shop Order Transaction Notify')) ?>
    </h2>
    <?= $this->Form->create($shopOrderTransactionNotify, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                    echo $this->Form->control('shop_order_transaction_id', ['options' => $shopOrderTransactions]);
                echo $this->Form->control('type');
                echo $this->Form->control('engine');
                echo $this->Form->control('request_ip');
                echo $this->Form->control('request_url');
                echo $this->Form->control('request_json');
                echo $this->Form->control('is_valid');
                echo $this->Form->control('is_processed');
        ?>
        </div>

    <?= $this->Form->button(__d('shop', 'Submit')) ?>
    <?= $this->Form->end() ?>

</div>