<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','My Orders'), ['controller' => 'Orders', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','View order details and status'), ['controller' => 'Orders', 'action' => 'view', $order->uuid, 'ref' => 'breadcrumb']);
?>
<?php $this->loadHelper('Banana.Status'); ?>
<?php $this->assign('title', __d('shop', 'Order {0}', $order->nr_formatted)); ?>
<div class="shop order view container">

    <h2><?= __d('shop','Order') ?>&nbsp;<?= $order->nr_formatted; ?></h2>

    <?= $this->element('Shop.Order/messages'); ?>
    <?= $this->element('Shop.Order/payment_status'); ?>
    <?= $this->element('Shop.Order/order_info'); ?>
    <hr />

    <div class="alert alert-info">
        <strong><i class="fa fa-lock"></i>&nbsp;<?= __d('shop', 'Order details only available for logged in customers'); ?></strong>
        <p>
            <?= $this->Html->link(__d('shop', 'Login here'), ['_name' => 'user:login']); ?>
        </p>
    </div>
</div>