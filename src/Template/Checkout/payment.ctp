<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'payment'); ?>
<?php $this->assign('heading', __d('shop','Select your payment method')); ?>
<?php
//$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Payment'), ['controller' => 'Checkout', 'action' => 'payment', 'ref' => 'breadcrumb']);
?>
<div class="shop checkout step payment">

    <?php foreach ($paymentMethods as $alias => $paymentMethod): ?>
        <?php
        $element = 'Shop.Checkout/Payment/' . $alias . '/select';
        ?>
        <div class="payment-method row">
            <div class="col-md-8">
                <h3 style="margin-top: 0;"><?= h($paymentMethod['name']); ?></h3>
                <?php if ($this->elementExists($element)): ?>
                    <?= $this->element($element); ?>
                <?php endif; ?>
            </div>
            <div class="col-sm-4">
                <?= $this->Form->postLink(
                    __d('shop','Select'),
                    ['plugin' => 'Shop', 'controller' => 'Checkout', 'action' => 'payment', 'change_type' => true],
                    ['class' => 'btn btn-primary', 'data' => ['payment_type' => $alias]]
                ); ?>
            </div>
        </div>
        <hr />
    <?php endforeach; ?>


    <?php debug($paymentMethods); ?>
    <?php debug($paymentOptions); ?>
</div>
<script>
    $(document).ready(function() {
        return;

        // hide all payment method descriptions
        $('.payment-method-select:not(:checked)').hide();
        // show selected payment method description
        $('input[name="payment_type"]:checked')
            .next('.payment-method')
            .addClass('checked')
            .find('.payment-method-select').show();
        // toggle payment method descriptions on click
        $('input[name="payment_type"]').click(function(ev) {

            var $pm = $(this).next('.payment-method');
            if ($pm.hasClass('checked')) {
                // Already active
                $pm.find('.payment-method-select').show();
            } else {
                $('.payment-method').removeClass('checked');
                $('.payment-method-select').slideUp();
                $pm.addClass('checked');
                $pm.find('.payment-method-select').slideDown();
            }

            //ev.preventDefault();
            //return false;
        });
    });
</script>