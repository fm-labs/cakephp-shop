<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','My Orders'), ['controller' => 'Orders', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','View order details and status'), ['controller' => 'Orders', 'action' => 'view', $order->uuid, 'ref' => 'breadcrumb']);
?>
<?php $this->assign('title', __d('shop','Order {0}', $order->nr_formatted)); ?>
<div class="shop order view">

    <h1><?= __('Your Order #{0}', $order->nr_formatted); ?></h1>
    <h3><?= __('Reference number'); ?>:&nbsp;<?= h($order->nr_formatted); ?></h3>
    <hr />

    <div class="row">
        <div class="col-md-6">
            <h2>
                <?= __d('shop','Billing address'); ?>
            </h2>
            <?php echo $this->element('Shop.Order/billing_address'); ?>


        </div>
        <div class="col-md-6">
            <h2>
                <?= __d('shop','Shipping address'); ?>
            </h2>
            <?php echo $this->element('Shop.Order/shipping_address'); ?>

        </div>
    </div>

    <div class="row">
        <div class="col-md-6">

            <h2>
                <?= __d('shop','Payment Method') ?>
            </h2>
            <div class="inner">
                <?php $paymentMethods = \Cake\Core\Configure::read('Shop.PaymentMethods') ?>
                <h5><?= h($paymentMethods[$order->payment_type]['name']); ?></h5>
                <p>
                    <?php
                    $element = 'Shop.Checkout/Payment/' . $order->payment_type . '/review';
                    if ($this->elementExists($element)) {
                        echo $this->element($element);
                    }
                    ?>
                </p>
            </div>

        </div>
        <div class="col-md-6">

            <h2>
                <?= __d('shop','Shipping Method') ?>
            </h2>
            <div class="inner">

                <?php $shippingMethods = \Cake\Core\Configure::read('Shop.ShippingMethods') ?>
                <h5><?= h($shippingMethods[$order->shipping_type]['name']); ?></h5>
                <div class="desc" style="font-size: 90%;">
                    <?= $this->Content->userHtml($shippingMethods[$order->shipping_type]['desc']); ?>
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

    <div class="row">
        <div class="col-md-12">
            <h2><?= __d('shop','Additional Information') ?></h2>

            <?= $this->Form->input('customer_notes', ['label' => __d('shop','Additional Notes')]); ?>
            <?= $this->Form->input('customer_email', [
                //'required' => true,
                'label' => __d('shop','Email for notifications') . '*',
            ]); ?>
            <?= $this->Form->input('customer_phone', [
                'required' => true,
                'label' => __d('shop','Callback phone number') . '*',
            ]); ?>
            <?= $this->Form->input('agree_terms', ['label' => __d('shop','Agree to Terms & Conditions') . '*']); ?>
            <?= '' //$this->Form->input('agree_newsletter', ['label' => __d('shop','Agree Newsletter')]); ?>
        </div>
    </div>

    <div class="ui actions" style="text-align: right;">
        <?php echo $this->Html->link(__d('shop', 'Cancel order'), ['action' => 'index', 'op' => 'cancel'], ['class' => 'btn']); ?>
    </div>

</div>