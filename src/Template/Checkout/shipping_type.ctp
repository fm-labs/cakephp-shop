<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'shipping'); ?>
<?php $this->assign('heading', __d('shop','Select shipping method')); ?>
<?php $this->loadHelper('Content.Content'); ?>
<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index']);
$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Shipping'), ['controller' => 'Checkout', 'action' => 'shipping', 'ref' => 'breadcrumb']);
?>
<div class="shop checkout step shipping">
    <div class="form">
        <?= $this->Form->create($order, ['url' => ['step' => 'shipping', 'change_type' => 1]]); ?>
        <?= ''//$this->Form->input('shipping_type', ['options' => $shippingOptions, 'label' => false, 'empty' => false]); ?>

        <?php foreach ($shippingMethods as $type => $shippingMethod): ?>
            <div class="shipping-method" data-shipping-method="<?= $type; ?>">
                <input name="shipping_type" type="radio" value="<?= $type; ?>" <?= ($order->shipping_type == $type) ? 'checked': '' ?>>
                <label for="shipping_type" class="label"><?= h($shippingMethod['name']) ?></label>
                <div class="desc" style="padding-left: 40px;">
                    <?= $this->Content->userHtml($shippingMethod['desc']); ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php echo $this->Form->error('shipping_type'); ?>

        <div style="text-align: right; margin-top: 1em;">
            <?= $this->Form->submit(__d('shop', 'Continue'), ['class' => 'btn btn-primary']); ?>
        </div>

        <?= $this->Form->end(); ?>
    </div>
</div>