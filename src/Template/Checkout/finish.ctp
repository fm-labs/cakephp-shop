<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'finish'); ?>
<?php $this->assign('heading', __d('shop','Thank you for your order!')); ?>
<div class="">
    <h3>
        <?= __d('shop', 'Your order reference is #{0}', $order->nr_formatted); ?>
    </h3>
    <p>
        <?= __d('shop', 'A confirmation email has been sent to {0}', $order->customer_email); ?>
    </p>

    <?= $this->Html->link(__d('shop', 'Go back to shop'), ['controller' => 'ShopCategories', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>
    <?= $this->Html->link(__d('shop', 'View order details'), ['controller' => 'Orders', 'action' => 'view', $order->uuid],['class' => 'btn btn-primary']); ?>

</div>
