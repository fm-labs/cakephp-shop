<?php //$this->extend('Shop.Checkout/base'); ?>
<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Review'), ['controller' => 'Checkout', 'action' => 'review', 'ref' => 'breadcrumb']);
?>
<?php $this->assign('step_active', 'review'); ?>
<?php $this->assign('heading', __d('shop','Review your order')); ?>
<div class="shop checkout step review">

    <h2><?= __d('shop','Review your order') ?></h2>

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><?= __d('shop','Billing address'); ?></strong>
                    <small><?= $this->Html->link(__d('shop', 'Change'), ['action' => 'billing', 'change' => true]); ?></small>
                </div>
                <div class="panel-body">
                    <?= $this->element('Shop.Order/billing_address', ['order' => $order]); ?>
                    > <?= $this->Html->link(__d('shop','Edit'), ['action' => 'billing', 'ref' => 'review']); ?>
                </div>
            </div>

        </div>
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><?= __d('shop','Shipping address'); ?></strong>
                    <small><?= $this->Html->link(__d('shop', 'Change'), ['action' => 'shipping', 'change' => true]); ?></small>
                </div>
                <div class="panel-body">
                    <?= $this->element('Shop.Order/shipping_address', ['order' => $order]); ?>
                    > <?= $this->Html->link(__d('shop','Edit'), ['action' => 'shipping', 'ref' => 'review']); ?>

                </div>
            </div>

        </div>
    </div>

    <div class="row equal">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading"><strong><?= __d('shop','Payment') ?></strong></div>
                <div class="panel-body">
                    <?php $paymentMethods = \Cake\Core\Configure::read('Shop.PaymentMethods') ?>
                    <h5><?= h($paymentMethods[$order->payment_type]['name']); ?></h5>
                    <p>
                        <?php
                        $element = 'Shop.Checkout/Payment/' . $order->payment_type . '_review';
                        if ($this->elementExists($element)) {
                            echo $this->element($element);
                        }
                        ?>
                    </p>
                    > <?= $this->Html->link(__d('shop','Edit'), ['action' => 'payment', 'ref' => 'review']); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading"><strong><?= __d('shop','Shipping') ?></strong></div>
                <div class="panel-body">
                    <?php $shippingMethods = \Cake\Core\Configure::read('Shop.ShippingMethods') ?>
                    <h5><?= h($shippingMethods[$order->shipping_type]['name']); ?></h5>
                    <p><?= h($shippingMethods[$order->shipping_type]['desc']); ?></p>
                    > <?= $this->Html->link(__d('shop','Edit'), ['action' => 'shipping', 'ref' => 'review']); ?>
                </div>
            </div>

        </div>
    </div>



    <div class="panel panel-default">
        <div class="panel-heading"><strong><?= __d('shop','Order Items') ?></strong></div>
        <div class="panel-body">
            <?= $this->element('Shop.Checkout/cart'); ?>
        </div>
    </div>

    <?= $this->Form->create($order, ['url' => ['action' => 'submit']]); ?>
    <?= $this->Form->hidden('_x_checkout', ['value' => 'submit']); ?>
    <?= $this->Form->hidden('_x_tkn', ['value' => uniqid('ckkout')]); ?>

    <div class="panel panel-default">
        <div class="panel-heading"><strong><?= __d('shop','Additional Information') ?></strong></div>
        <div class="panel-body">
            <?= $this->Form->input('customer_notes', ['label' => __d('shop','Additional Notes')]); ?>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading"><strong><?= __d('shop','Terms') ?></strong></div>
        <div class="panel-body">
            <?= $this->Form->input('customer_phone', [
                'required' => true,
                'label' => __d('shop','Callback phone number'),
            ]); ?>
            <?= $this->Form->input('agree_terms', ['label' => __d('shop','Agree to Terms & Conditions') . '*']); ?>
            <?= '' //$this->Form->input('agree_newsletter', ['label' => __d('shop','Agree Newsletter')]); ?>
        </div>
    </div>

    <hr />
    <small>Die mit einem * gekennzeichenten Felder sind Pflichtfelder</small>

    <hr />
    <div class="ui actions" style="text-align: right;">
        <?= $this->Form->button(__d('shop','Order Now'), ['class' => 'ui primary submit button']); ?>
    </div>
    <?= $this->Form->end(); ?>

</div>