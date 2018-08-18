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

    <?= $this->Form->button(__d('shop', 'Submit')) ?>
    <?= $this->Form->end() ?>

</div>