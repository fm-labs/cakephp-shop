<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'payment'); ?>
<?php $this->assign('heading', __d('shop','Select your payment method')); ?>
<?php
//$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Payment'), ['controller' => 'Checkout', 'action' => 'payment', 'ref' => 'breadcrumb']);
?>
<div class="shop checkout step payment">

    <?php foreach ($paymentMethods as $alias => $paymentMethod): ?>
        <?php
        $element = 'Shop.Payment/' . $alias . '/checkout';
        ?>
        <div class="payment-method row">
            <div class="col-md-8">
                <h3 style="margin-top: 0;"><?= h($paymentMethod['name']); ?></h3>
                <?php if ($this->elementExists($element)): ?>
                    <?= $this->element($element); ?>
                <?php endif; ?>
            </div>
            <div class="col-sm-4">
                <?php if ($alias === $order->payment_type): ?>
                    <?= $this->Html->link(
                        __d('shop','Currently selected payment method'),
                        ['plugin' => 'Shop', 'controller' => 'Checkout', 'action' => 'payment'],
                        ['class' => 'btn btn-inverse']
                    ); ?>
                <?php else: ?>
                    <?= $this->Form->create(null); ?>
                    <?= $this->Form->hidden('op', ['value' => 'change']); ?>
                    <?= $this->Form->hidden('payment_type', ['value' => $alias]); ?>
                    <?= $this->Form->button(__d('shop','Select'),
                        ['class' => 'btn btn-primary']); ?>
                    <?= $this->Form->end(); ?>

                <?php endif; ?>
            </div>
        </div>
        <hr />
    <?php endforeach; ?>

    <?php if ($order->payment_type): ?>
    <div class="text-right">
        <?= $this->Html->link(__d('shop','Continue'), ['action' => 'next'], ['class' => 'btn btn-primary']); ?>
    </div>
    <?php endif; ?>

    <?php debug($paymentMethods); ?>
</div>