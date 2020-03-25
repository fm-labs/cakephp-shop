<?php $this->Breadcrumbs->add(__d('shop', 'Shop Order Transactions'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Edit {0}', __d('shop', 'Shop Order Transaction'))); ?>
<?php $this->Toolbar->addPostLink(
    __d('shop', 'Delete'),
    ['action' => 'delete', $shopOrderTransaction->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopOrderTransaction->id)]
)
?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Order Transactions')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
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
        <?= __d('shop', 'Edit {0}', __d('shop', 'Shop Order Transaction')) ?>
    </h2>
    <?= $this->Form->create($shopOrderTransaction, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                    echo $this->Form->control('shop_order_id', ['options' => $shopOrders]);
                echo $this->Form->control('type');
                echo $this->Form->control('engine');
                echo $this->Form->control('currency_code');
                echo $this->Form->control('value');
                echo $this->Form->control('status');
                echo $this->Form->control('ext_txnid');
                echo $this->Form->control('ext_status');
                echo $this->Form->control('init_response');
                echo $this->Form->control('init_request');
                echo $this->Form->control('redirect_url');
                echo $this->Form->control('custom1');
                echo $this->Form->control('custom2');
        ?>
        </div>

    <?= $this->Form->button(__d('shop', 'Submit')) ?>
    <?= $this->Form->end() ?>

</div>