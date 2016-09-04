<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'cart'); ?>
<?php $this->assign('heading', __d('shop','Cart')); ?>
<div class="shop checkout step cart">
    <?= $this->element('Shop.Checkout/cart'); ?>

    <div class="ui actions" style="text-align: right;">
        <?= $this->Html->link(__d('shop','Continue'), ['action' => 'next'], ['class' => 'ui primary button']); ?>
    </div>
</div>
