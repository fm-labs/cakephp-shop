<div class="payment index container">

    <h1><?= __('Payment'); ?></h1>

    <?php if (isset($paymentUrl)): ?>
        <?php echo $this->Html->link('Continue to payment provider', $paymentUrl, ['class' => 'btn btn-lg btn-primary', 'target' => '_blank']); ?>
        <p>
            <?= $paymentUrl; ?>
        </p>
    <?php endif; ?>


    <hr />
    <?= $this->Html->link(
        __('Back to order'),
        ['controller' => 'Orders', 'action' => 'view', $order->uuid],
        ['class' => 'btn btn-default']
    ); ?>


    <?php if (isset($debugInfo)): ?>
        <hr/>
        <pre><?php echo print_r($debugInfo, true) ?></pre>
    <?php endif; ?>
</div>