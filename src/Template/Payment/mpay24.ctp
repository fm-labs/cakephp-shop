<div class="index container">

    <?php if (isset($paymentUrl)): ?>
        <?php echo $this->Html->link('Continue to payment provider', $paymentUrl, ['class' => 'btn btn-lg btn-primary', 'target' => '_blank']); ?>
        <p>
            <?= $paymentUrl; ?>
        </p>
    <?php endif; ?>

    <?php if (isset($mdxi)): ?>
        <hr/>
        <pre><?php echo h($mdxi) ?></pre>
    <?php endif; ?>
</div>