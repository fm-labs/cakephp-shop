<?php
/** @var \Shop\Model\Entity\ShopOrder $order */
?>
<div class="table-responsive">
    <table class="table table-condensed">
        <tbody>
        <tr class="text-end">
            <th style="width:50%"><?= __d('shop', 'Subtotal') ?>:</th>
            <td><?= $this->Number->currency($order->items_value_net, $order->currency); ?></td>
        </tr>
        <tr class="text-end">
            <th><?= __d('shop', 'Shipping') ?>:</th>
            <td><?= $this->Number->currency($order->shipping_value_net, $order->currency); ?></td>
        </tr>
        <tr class="text-end">
            <th><?= __d('shop', 'Tax') ?>:</th>
            <td><?= $this->Number->currency($order->items_value_tax, $order->currency); ?></td>
        </tr>
        <tr class="text-end">
            <th><?= __d('shop', 'Total') ?>:</th>
            <td><?= $this->Number->currency($order->items_value_taxed, $order->currency); ?></td>
        </tr>
        <tr class="text-end">
            <th><?= __d('shop', 'Discount') ?>:</th>
            <td><?= $this->Number->currency($order->coupon_value * -1, $order->currency); ?></td>
        </tr>
        <tr class="text-end">
            <th><?= __d('shop', 'Order total') ?>:</th>
            <td><?= $this->Number->currency($order->order_value_total, $order->currency); ?></td>
        </tr>
        </tbody>
    </table>
</div>
