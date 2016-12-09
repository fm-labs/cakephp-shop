<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'cart'); ?>
<?php $this->assign('heading', __d('shop','Cart')); ?>
<?php
$this->Breadcrumbs->add(__('Shop'), ['_name' => 'shop:index']);
$this->Breadcrumbs->add(__('Checkout'), ['controller' => 'Checkout', 'action' => 'index']);
$this->Breadcrumbs->add(__('Cart'), ['controller' => 'Checkout', 'action' => 'cart']);
?>
<?php $this->start('active_step'); ?>
ACTIVE STEP!
<?php $this->end(); ?>
<div class="shop checkout step cart">
    <?= $this->element('Shop.Checkout/cart'); ?>

    <div class="ui actions" style="text-align: right;">
        <?= $this->Html->link(__d('shop','Continue'), ['action' => 'next'], ['class' => 'ui primary button']); ?>
    </div>
</div>
