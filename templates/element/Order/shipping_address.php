<?php
if (!isset($order) || !$order) return;
if ($order->shipping_use_billing) {
    echo $this->element('Shop.Order/billing_address');
    //echo __d('shop', 'Same as billing address');
    return;
}
echo $this->element('Shop.address', ['address' => $order->getShippingAddress()]);