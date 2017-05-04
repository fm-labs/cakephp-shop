<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','My Orders'), ['controller' => 'Orders', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','View order details and status'), ['controller' => 'Orders', 'action' => 'view', $order->uuid, 'ref' => 'breadcrumb']);
?>
<?php $this->loadHelper('Banana.Status'); ?>
<?php $this->assign('title', __d('shop', 'Order {0}', $order->nr_formatted)); ?>
<div class="shop order view container">

    <?php if ($this->request->query('order_complete')): ?>
    <div class="alert alert-success">
        <strong><?= __('Your order has been submitted'); ?></strong>
        <p><?= __('A confirmation email has been sent to <strong>%s</strong>', $order->customer_email); ?></p>
    </div>
    <?php endif; ?>

    <h2><?= __d('shop','Order {0}', $order->nr_formatted); ?></h2>
    <p>
        <strong><?= __d('shop','Date of purchase: {0}', $this->Time->nice($order->submitted)); ?></strong>
    </p>

    <hr />
    <div class="row">
        <div class="col-md-12">
            <h2><?= __d('shop','Order Items') ?></h2>
            <?= $this->element('Shop.Checkout/cart'); ?>
        </div>
    </div>
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

    <hr />

    <div class="row">
        <div class="col-md-6">

            <h2>
                <?= __d('shop','Payment Method') ?>
            </h2>
            <div class="inner">
                <?php $paymentMethods = \Cake\Core\Configure::read('Shop.Payment.Engines') ?>
                <!--
                <h5><?= h($paymentMethods[$order->payment_type]['name']); ?></h5>
                -->
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

                <?php $shippingMethods = \Cake\Core\Configure::read('Shop.Shipping.Engines') ?>
                <!--
                <h5><?= h($shippingMethods[$order->shipping_type]['name']); ?></h5>
                -->
                <p>
                    <?php
                    $element = 'Shop.Checkout/Shipping/' . $order->shipping_type . '/review';
                    if ($this->elementExists($element)) {
                        echo $this->element($element);
                    }
                    ?>
                </p>
            </div>
        </div>
    </div>


    <!--
    <hr />
    <div class="row">
        <div class="col-md-12">
            <h2><?= __d('shop','Additional Information') ?></h2>

            <strong>Customer Phone number</strong>
            <p><?= h($order->customer_phone); ?></p>

            <strong>Customer Email</strong>
            <p><?= h($order->customer_email); ?></p>

            <strong>Customer Notes</strong>
            <p><?= h($order->customer_notes); ?></p>

        </div>
    </div>
    -->


    <hr />
    <div class="row">
        <div class="col-md-12">
            <p class="text-center">
                <?= $this->Html->link(__('List all orders'), ['action' => 'index'], ['class' =>'btn btn-default']); ?>
            </p>
        </div>
    </div>
</div>