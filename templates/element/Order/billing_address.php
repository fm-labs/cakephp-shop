<?php
if (!isset($order) || !$order) return;
echo $this->element('Shop.address', ['address' => $order->getBillingAddress()]);