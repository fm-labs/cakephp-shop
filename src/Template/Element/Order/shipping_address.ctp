<?php if ($order->shipping_use_billing) {
    echo $this->element('Shop.Order/billing_address', compact('order'));
    return;
} ?>
<div class="shipping address">
    <?= h($order->shipping_first_name); ?>
    <?= h($order->shipping_last_name); ?><br />
    <?= h($order->shipping_street); ?><br />
    <?= h($order->shipping_zipcode); ?>
    <?= h($order->shipping_city); ?><br />
    <?= h($order->shipping_country); ?>
</div>