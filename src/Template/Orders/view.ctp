<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','My Orders'), ['controller' => 'Orders', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','View order details and status'), ['controller' => 'Orders', 'action' => 'view', $order->uuid, 'ref' => 'breadcrumb']);
?>
<?php $this->loadHelper('Banana.Status'); ?>
<?php $this->assign('title', __d('shop','Order {0}', $order->nr_formatted)); ?>
<div class="shop order view container">

    <h1><?= __d('shop','Order from {0}', $this->Time->nice($order->submitted)); ?></h1>
    <h3><?= __d('shop','Reference number: #{0}', $order->nr_formatted); ?></h3>
    <hr />
    <h3>Status: <?= $this->Status->label($order->status); ?></h3>
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

    <hr />


    <div class="row">
        <div class="col-md-12">
            <h2><?= __d('shop','Additional Information') ?></h2>

            <strong>Customer Notes</strong>
            <p><?= h($order->customer_notes); ?></p>

            <strong>Customer Email</strong>
            <p><?= h($order->customer_email); ?></p>

            <strong>Customer Phone number</strong>
            <p><?= h($order->customer_phone); ?></p>

        </div>
    </div>

</div>