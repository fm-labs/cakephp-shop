<?php
$this->extend('Shop.Checkout/base');

/** @var \Shop\Model\Entity\ShopOrder $order */
$order = $this->get('order')
//$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Review order'), ['controller' => 'Checkout', 'action' => 'review', 'ref' => 'breadcrumb']);
?>
<?php $this->assign('step_active', 'review'); ?>
<?php $this->assign('heading', __d('shop','Review your order')); ?>
<div class="shop checkout step review">

    <div class="row">
        <div class="col-md-6">
            <div class="d-flex flex-row justify-content-between border-bottom my-3">
                <div class="h4">
                    <?= __d('shop','Billing address'); ?>
                </div>
                <small><?= $this->Html->link(
                        __d('shop','Edit'),
                        ['action' => 'billing_address', $order->cartid, '?' => ['edit' => 1, 'ref' => 'order-summary']]); ?></small>
            </div>
            <?= $this->element('Shop.Order/billing_address'); ?>
        </div>

        <div class="col-md-6">
            <div class="d-flex flex-row justify-content-between border-bottom my-3">
                <div class="h4">
                    <?= __d('shop','Shipping address'); ?>
                </div>
                <small><?php $this->Html->link(
                        __d('shop','Edit'),
                        ['action' => 'shipping_address', $order->cartid, '?' => ['edit' => 1, 'ref' => 'order-summary']]); ?></small>
            </div>
            <?= $this->element('Shop.Order/shipping_address'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">

            <div class="d-flex flex-row justify-content-between border-bottom my-3">
                <div class="h4">
                    <?= __d('shop','Payment Method') ?>
                </div>
                <small><?= $this->Html->link(
                        __d('shop','Edit'),
                        ['action' => 'payment', $order->cartid, '?' => ['edit' => 1, 'ref' => 'order-summary']]); ?></small>
            </div>
            <div class="inner mb-3">
                <div class="desc payment-desc">
                    <?php
                    $element = 'Shop.Payment/' . $order->payment_type . '/order';
                    if ($this->elementExists($element)) {
                        echo $this->element($element);
                    }
                    ?>
                </div>
            </div>

        </div>
        <div class="col-md-6">

            <div class="d-flex flex-row justify-content-between border-bottom my-3">
                <div class="h4">
                    <?= __d('shop','Shipping Method') ?>
                </div>
                <small><?= $this->Html->link(
                        __d('shop','Edit'),
                        ['action' => 'shipping', $order->cartid, '?' => ['edit' => 1, 'ref' => 'order-summary']]); ?></small>
            </div>
            <div class="inner mb-3">
                <div class="desc shipping-desc">
                    <?php
                    $element = 'Shop.Shipping/' . $order->shipping_type . '/order';
                    if ($this->elementExists($element)) {
                        echo $this->element($element);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <h2><?= __d('shop','Order Items') ?></h2>
            <?= $this->element('Shop.Checkout/cart'); ?>
        </div>
    </div>


    <?= $this->Form->create($order); ?>
    <?= $this->Form->hidden('_op', ['value' => 'submit']); ?>
    <?= $this->Form->hidden('_tkn', ['value' => uniqid('ckkout')]); ?>

    <div class="row">
        <div class="col-md-12">
            <!--
            <h2><?= __d('shop','Additional Information') ?></h2>
            -->

            <?php if (\Cake\Core\Configure::read('Shop.Checkout.customerNotes')): ?>
            <?= $this->Form->control('customer_notes', ['label' => __d('shop','Additional Notes')]); ?>
            <?php endif; ?>

            <?php if (\Cake\Core\Configure::read('Shop.Checkout.customerPhone')): ?>
            <?= $this->Form->control('customer_phone', [
                'required' => true,
                'label' => __d('shop','Callback phone number') . '*',
            ]); ?>
            <?php endif; ?>

            <?php
            //@TODO Use a configured content page for terms page/popup
            $termsUrl = (Cake\Core\Configure::read('Shop.Pages.termsUrl')) ?: ['plugin' => 'Content', 'controller' => 'Pages', 'action' => 'view', 'slug' => 'terms'];
            $termsLink = $this->Html->link(__d('shop','I agree to the terms & conditions and accept the privacy policy'),
                $termsUrl, ['target' => '_blank', 'class' => 'link-modal']);
            echo $this->Form->control('agree_terms', ['label' => $termsLink . '*', 'escape' => false]);
            ?>
        </div>
    </div>

    <div class="ui actions" style="text-align: right;">
        <!--
        <?php echo $this->Html->link(__d('shop', 'Cancel order'), ['action' => 'index', $order->cartid, 'op' => 'cancel'], ['class' => 'btn']); ?>
        -->
        <?= $this->Form->button(__d('shop','Order Now'), ['class' => 'btn btn-primary btn-lg']); ?>
    </div>
    <?= $this->Form->end(); ?>

    <hr />
    <small>Die mit einem * gekennzeichenten Felder sind Pflichtfelder</small>
    <hr />
</div>