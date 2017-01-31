<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'payment'); ?>
<?php $this->assign('heading', __d('shop','Select your payment method')); ?>
<?php
//$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
//$this->Breadcrumbs->add(__d('shop','Payment'), ['controller' => 'Checkout', 'action' => 'payment', 'ref' => 'breadcrumb']);
?>
<div class="shop checkout step payment">

    <!--
    <?php foreach ($paymentMethods as $alias => $paymentMethod): ?>
        <?php
        $element = 'Shop.Checkout/Payment/' . $alias . '/select';
        ?>
        <div class="payment-method row">
            <div class="col-md-1">
                <input type="radio" name="payment_type" />
            </div>
            <div class="col-md-11">
                <div class="payment-method-label">
                    <label for="payment_type"><?= $this->Form->postLink(
                            $paymentMethod['name'],
                            ['plugin' => 'Shop', 'controller' => 'Checkout', 'step' => 'payment', 'change_type' => true],
                            ['data' => ['payment_type' => $alias]]
                        ); ?></label>
                </div>
                <div class="payment-method-desc">
                    <?php if ($this->elementExists($element)): ?>
                        <?= $this->element($element); ?>
                    <?php else: ?>
                        <div class="payment-method-logo"><?= h($paymentMethod['logoUrl']); ?></div>
                        <div class="payment-method-desc"><?= h($paymentMethod['desc']); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    -->


    <?php
    array_walk($paymentOptions, function (&$val, $idx) use ($order) {

        $element = 'Shop.Checkout/Payment/' . $idx . '/select';
        if ($this->elementExists($element)) {
            $val = $this->element($element);
        }
    });

    ?>
    <div class="form">
        <?= $this->Form->create($order, ['url' => ['action' => 'step', 'step' => 'payment', 'change_type' => true]]); ?>
        <?= $this->Form->input('payment_type', [
            'type' => 'radio',
            'options' => $paymentOptions,
            'label' => false,
            'escape' => false,
            'class' => 'wide'
        ]); ?>

        <div class="actions text-right">
            <?= $this->Form->button(__d('shop','Continue'), ['class' => 'btn btn-primary']); ?>
        </div>

        <?= $this->Form->end(); ?>
    </div>

    <?php debug($paymentMethods); ?>
    <?php debug($paymentOptions); ?>
</div>
<script>
    $(document).ready(function() {

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