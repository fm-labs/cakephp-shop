<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'shipping'); ?>
<?php $this->assign('heading', __d('shop','Select shipping address')); ?>
<?php $this->loadHelper('Content.Content'); ?>
<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index']);
$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Shipping'), ['controller' => 'Checkout', 'action' => 'shipping', 'ref' => 'breadcrumb']);
?>
<div class="shop checkout step shipping">

    <div class="shipping address">

        <?php if ($order->is_shipping_selected && !$this->request->query('change')): ?>

            <h2>Lieferadresse</h2>
            <div class="selected address">
                <?= $order->shipping_first_name ?> <?= $order->shipping_last_name ?><br />
                <?= $order->shipping_street ?><br />
                <?= $order->shipping_zipcode ?> <?= $order->shipping_city ?><br />
                <?= $order->shipping_country ?><br />

                <?= $this->Html->link(__d('shop', 'Change shipping address'), ['action' => 'shipping', 'change' => true], ['class' => 'btn btn-default']); ?>
            </div>

        <?php else: ?>

            <?php if ($shippingAddresses): ?>
                <h4><?= __d('shop', 'Stored addresses'); ?></h4>
                <?php foreach($shippingAddresses as $address): ?>
                    <div class="address">
                        <?= $address->first_name ?> <?= $address->last_name ?>,
                        <?= $address->street ?>,
                        <?= $address->zipcode ?> <?= $address->city ?>,
                        <?= $address->country ?><br />
                        <?= $this->Html->link(
                            __d('shop', 'Ship to this address'),
                            ['action' => 'shipping_select', $address->id], ['class' => 'btn btn-default']); ?>
                    </div>
                <?php endforeach; ?>
                <h4><?= __d('shop', 'Add new shipping address'); ?></h4>
                <hr />
            <?php endif; ?>

            <?= $this->Form->create($shippingAddress, []); ?>
            <?= $this->Form->input('first_name', ['label' => __d('shop','First Name')]); ?>
            <?= $this->Form->input('last_name', ['label' => __d('shop','Last Name')]); ?>
            <?= '' // $this->Form->input('name', ['label' => __d('shop','Name')]); ?>
            <?= $this->Form->input('street', ['label' => __d('shop','Street')]); ?>
            <?= '' //$this->Form->input('taxid', ['label' => __d('shop','Tax Id')]); ?>
            <?= $this->Form->input('zipcode', ['label' => __d('shop','Zipcode')]); ?>
            <?= $this->Form->input('city', ['label' => __d('shop','City')]); ?>
            <?= $this->Form->input('country', ['label' => __d('shop','Country')]); ?>

            <div class="actions" style="text-align: right; margin-top: 1em;">
                <?= $this->Form->submit(__d('shop','Continue'), ['class' => 'btn btn-primary']); ?>
            </div>
            <?= $this->Form->end(); ?>


        <?php endif; ?>
    </div>
</div>
<script>
    /*
    $(document).ready(function() {
        var $toggle = $('#toggle-shipping-use-billing');
        var $container = $('#shipping-newaddress');

        $toggle.on('change', function(e) {
            var val = $toggle.val();
            var checked = $toggle.is(':checked');

            if (checked && $container.not(':hidden')) {
                $container.hide();
            } else if (!checked && $container.is(':hidden')) {
                $container.show();
            }
        }).trigger('change');
    });
    */
</script>