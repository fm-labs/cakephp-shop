<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','My Orders'), ['controller' => 'Orders', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','View order details and status'), ['controller' => 'Orders', 'action' => 'view', $order->uuid, 'ref' => 'breadcrumb']);
?>
<?php $this->loadHelper('Cupcake.Status'); ?>
<?php $this->assign('title', __d('shop', 'Order {0}', $order->nr_formatted)); ?>
<div class="shop order view container">

    <h2><?= __d('shop','Order purchased on {0}', $this->Time->nice($order->submitted)); ?></h2>

    <hr />

    <?= $this->element('Shop.Order/messages'); ?>
    <?= $this->element('Shop.Order/payment_status'); ?>
    <?= $this->element('Shop.Order/order_info'); ?>


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
                    $element = 'Shop.Payment/' . $order->payment_type . '/order';
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
                    $element = 'Shop.Shipping/' . $order->shipping_type . '/order';
                    if ($this->elementExists($element)) {
                        echo $this->element($element);
                    }
                    ?>
                </p>
            </div>
        </div>
    </div>

    <hr />
    <div class="row">
        <div class="col-md-12">
            <p class="text-center">
                <?= $this->Html->link(__d('shop','List all orders'), ['action' => 'index'], ['class' =>'btn btn-default']); ?>
            </p>
        </div>
    </div>

</div>