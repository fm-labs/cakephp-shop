<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'shipping'); ?>
<?php $this->assign('title', __d('shop','Checkout')); ?>
<?php $this->assign('heading', __d('shop','Select your shipping method')); ?>
<?php
//$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Payment'), ['controller' => 'Checkout', 'action' => 'shipping', 'ref' => 'breadcrumb']);
?>
<div class="shop checkout step shipping">

    <?php foreach ($shippingMethods as $alias => $shippingMethod): ?>
        <?php
        $element = 'Shop.Checkout/Shipping/' . $alias . '/select';
        ?>
        <div class="shipping-method row">
            <div class="col-md-8">
                <h3 style="margin-top: 0;"><?= h($shippingMethod['name']); ?></h3>
                <?php if ($this->elementExists($element)): ?>
                    <?= $this->element($element); ?>
                <?php endif; ?>
            </div>
            <div class="col-sm-4">
                <?= $this->Form->postLink(
                    __d('shop','Select'),
                    ['plugin' => 'Shop', 'controller' => 'Checkout', 'action' => 'shipping', 'change_type' => true],
                    ['class' => 'btn btn-primary', 'data' => ['shipping_type' => $alias]]
                ); ?>
            </div>
        </div>
        <hr />
    <?php endforeach; ?>


    <?php debug($shippingMethods); ?>
    <?php debug($shippingOptions); ?>
</div>
<script>
    $(document).ready(function() {
        return;

        // hide all shipping method descriptions
        $('.shipping-method-select:not(:checked)').hide();
        // show selected shipping method description
        $('input[name="shipping_type"]:checked')
            .next('.shipping-method')
            .addClass('checked')
            .find('.shipping-method-select').show();
        // toggle shipping method descriptions on click
        $('input[name="shipping_type"]').click(function(ev) {

            var $pm = $(this).next('.shipping-method');
            if ($pm.hasClass('checked')) {
                // Already active
                $pm.find('.shipping-method-select').show();
            } else {
                $('.shipping-method').removeClass('checked');
                $('.shipping-method-select').slideUp();
                $pm.addClass('checked');
                $pm.find('.shipping-method-select').slideDown();
            }

            //ev.preventDefault();
            //return false;
        });
    });
</script>