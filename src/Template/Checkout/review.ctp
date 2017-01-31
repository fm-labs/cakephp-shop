<?php $this->extend('Shop.Checkout/base'); ?>
<?php
//$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Review order'), ['controller' => 'Checkout', 'action' => 'review', 'ref' => 'breadcrumb']);
?>
<?php $this->assign('step_active', 'review'); ?>
<?php $this->assign('heading', __d('shop','Review your order')); ?>
<div class="shop checkout step review">

    <div class="row">
        <div class="col-md-6">

            <h2>
                <?= __d('shop','Billing address'); ?>
                <small><?= $this->Html->link(__d('shop','Change'), ['action' => 'billing', 'change' => true, 'ref' => 'review']); ?></small>
            </h2>
            <?= $this->element('Shop.Order/billing_address'); ?>


        </div>
        <div class="col-md-6">

            <h2>
                <?= __d('shop','Shipping address'); ?>
                <small><?= $this->Html->link(__d('shop','Change'), ['action' => 'shipping', 'change' => true, 'ref' => 'review']); ?></small>
            </h2>
            <?= $this->element('Shop.Order/shipping_address'); ?>

        </div>
    </div>

    <div class="row">
        <div class="col-md-6">

            <h2>
                <?= __d('shop','Payment Method') ?>
                <small><?= $this->Html->link(__d('shop','Change'), ['action' => 'payment', 'change_type' => true, 'ref' => 'review']); ?></small>
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
                <small><?= $this->Html->link(__d('shop','Change'), ['action' => 'shipping', 'change_type' => true, 'ref' => 'review']); ?></small>
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


    <?= $this->Form->create($order, ['url' => ['action' => 'review', 'submit' => true]]); ?>
    <?= $this->Form->hidden('_x_checkout', ['value' => 'submit']); ?>
    <?= $this->Form->hidden('_x_tkn', ['value' => uniqid('ckkout')]); ?>

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
    </div>

    <div class="ui actions" style="text-align: right;">
        <?php echo $this->Html->link(__d('shop', 'Cancel order'), ['action' => 'index', 'op' => 'cancel'], ['class' => 'btn']); ?>
        <?= $this->Form->button(__d('shop','Order Now'), ['class' => 'btn btn-primary']); ?>
    </div>
    <?= $this->Form->end(); ?>

    <hr />
    <small>Die mit einem * gekennzeichenten Felder sind Pflichtfelder</small>

    <hr />
    <?php debug($order->errors()); ?>
</div>