<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'shipping'); ?>
<?php $this->assign('heading', __d('shop','Select your shipping method')); ?>
<?php
//$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Payment'), ['controller' => 'Checkout', 'action' => 'shipping', 'ref' => 'breadcrumb']);
?>
<div class="shop checkout step shipping">

    <?php foreach ($shippingMethods as $alias => $shippingMethod): ?>
        <?php
        $element = 'Shop.Shipping/' . $alias . '/checkout';
        ?>
        <div class="shipping-method row">
            <div class="col-md-8">
                <h3 style="margin-top: 0;"><?= h($shippingMethod['name']); ?></h3>
                <?php if ($this->elementExists($element)): ?>
                    <?= $this->element($element); ?>
                <?php endif; ?>
            </div>
            <div class="col-sm-4">
                <?php if ($alias === $order->shipping_type): ?>
                    <strong>
                        <?= __d('shop','Currently selected shipping method'); ?>
                    </strong>
                <?php else: ?>
                    <?= $this->Form->create(null); ?>
                    <?= $this->Form->hidden('op', ['value' => 'change_type']); ?>
                    <?= $this->Form->hidden('shipping_type', ['value' => $alias]); ?>
                    <?= $this->Form->button(__d('shop','Select'),
                        ['class' => 'btn btn-primary']); ?>
                    <?= $this->Form->end(); ?>


                <?php endif; ?>
            </div>
        </div>
        <hr />
    <?php endforeach; ?>

    <?php if ($order->shipping_type): ?>
        <div class="text-right">
            <?= $this->Html->link(__d('shop','Continue'), ['action' => 'next', $order->cartid], ['class' => 'btn btn-primary']); ?>
        </div>
    <?php endif; ?>

    <?php debug($shippingMethods); ?>
</div>