<?php
/** @var \Shop\Model\Entity\ShopOrder $order */
?>
<table style="width:180mm; margin-bottom: 10mm;" cellpadding="5" cellspacing="0">
    <tr>
        <th style="width:10mm; background-color:#CCC; border-bottom:1px solid #000;"><?php
            echo __d('shop', 'Nr.'); ?></th>
        <th style="width:15mm; background-color:#CCC; border-bottom:1px solid #000;"><?php
            echo __d('shop', 'Menge'); ?></th>
        <th style="width:100mm; background-color:#CCC; border-bottom:1px solid #000;"><?php
            echo __d('shop', 'Bezeichnung'); ?></th>
        <th style="width:25mm;text-align:right; background-color:#CCC; border-bottom:1px solid #000;"><?php
            echo __d('shop', 'Preis'); ?></th>
        <th style="width:30mm;text-align:right; background-color:#CCC; border-bottom:1px solid #000;"><?php
            echo __d('shop', 'Betrag'); ?></th>
    </tr>
    <?php
    $i = 0;
    foreach ($order->shop_order_items as $item):
        ?>
        <tr>
            <td><?php echo ++$i; ?></td>
            <td><?php echo $item->amount; ?>x</td>
            <td><?php echo $item->title; ?></td>
            <td style="text-align:right;"><?php echo $this->Number->currency($item->item_value_net, 'EUR'); ?></td>
            <td style="text-align:right;"><?php echo $this->Number->currency($item->value_net, 'EUR'); ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="5">&nbsp;</td>
    </tr>
</table>
