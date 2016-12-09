<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'shipping'); ?>
<?php $this->assign('heading', __d('shop','Shipping')); ?>
<div class="shop checkout step shipping">

    <div class="shipping address">

        <?php if ($order->shipping_address_id && !$this->request->query('change')): ?>

            <h2>Lieferadresse</h2>
            <div class="selected address">
                <?= $order->shipping_first_name ?> <?= $order->shipping_last_name ?><br />
                <?= $order->shipping_street ?><br />
                <?= $order->shipping_zipcode ?> <?= $order->shipping_city ?><br />
                <?= $order->shipping_country ?><br />

                <?= $this->Html->link(__d('shop', 'Change shipping address'), ['action' => 'shipping', 'change' => true], ['class' => 'btn btn-default']); ?>
            </div>
            <hr />

            <h2>Versandart</h2>
            <div class="form">
                <?= $this->Form->create($order, ['url' => ['action' => 'shipping_type']]); ?>
                <?= $this->Form->input('shipping_type', ['options' => $shippingOptions, 'label' => false, 'empty' => false]); ?>

                <!--
                <?php foreach ($shippingMethods as $type => $shippingMethod): ?>
                    <div class="shipping-method" data-shipping-method="<?= $type; ?>">
                        <h4><?= h($shippingMethod['name']) ?></h4>
                        <p><?= h($shippingMethod['desc']); ?></p>
                    </div>
                <?php endforeach; ?>
                -->

                <div style="text-align: right; margin-top: 1em;">
                    <?= $this->Form->submit(__d('shop', 'Continue'), ['class' => 'ui primary button']); ?>
                </div>

                <?= $this->Form->end(); ?>
            </div>


        <?php else: ?>

            <h2>Lieferadresse wählen</h2>

            <?php if ($shippingAddresses): ?>
                <h4><?= __d('shop', 'Stored addresses'); ?></h4>
                <?php foreach($shippingAddresses as $address): ?>
                    <div class="address">
                        <?= $address->first_name ?> <?= $address->last_name ?><br />
                        <?= $address->street ?><br />
                        <?= $address->zipcode ?> <?= $address->city ?><br />
                        <?= $address->country ?><br />
                        <?= $this->Html->link(
                            __d('shop', 'Ship to this address'),
                            ['action' => 'shipping_select', $address->id], ['class' => 'btn btn-default']); ?>
                    </div>
                <?php endforeach; ?>
                <hr />
            <?php endif; ?>

            <h4><?= __d('shop', 'Add new shipping address'); ?></h4>
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
                <?= $this->Form->submit(__d('shop','Add shipping address and continue'), ['class' => 'ui primary button']); ?>
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