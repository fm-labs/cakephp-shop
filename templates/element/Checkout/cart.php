<?php $this->loadHelper('Bootstrap.Ui'); ?>
<table class="table table-striped">
    <thead>
    <tr>
        <th style="width: 20px;"><?= __d('shop','Pos'); ?></th>
        <th style="width: 100px;" class="text-right"><?= __d('shop','Amount'); ?></th>
        <th style="width: 20px;">&nbsp;</th>
        <th><?= __d('shop','Product'); ?></th>
        <th class="text-right"><?= __d('shop','Item Price'); ?></th>
        <th class="text-right"><?= __d('shop','Price'); ?></th>
    </tr>
    </thead>
    <?php if ($order && $order->shop_order_items): ?>
        <?php foreach ($order->shop_order_items as $idx => $item): ?>
            <tr>
                <td><?= h($idx+1) ?></td>
                <td class="text-right"><?= h($item->amount) ?></td>
                <td><?= h($item->unit); ?></td>
                <td><?= h($item->title); ?></td>
                <td class="text-right"><?= $this->Number->currency($item->item_value_display, 'EUR'); ?></td>
                <td class="text-right"><?= $this->Number->currency($item->value_display, 'EUR'); ?></td>
            </tr>
        <?php endforeach; ?>
        <tr style="">
            <td colspan="6"><span>&nbsp;</span></td>
        </tr>
        <?php if (\Shop\Lib\Shop::config('Price.displayNet')): ?>
            <tr style="font-weight: bold;">
                <td colspan="4">&nbsp;</td>
                <td class="text-right">Summe exkl. MwSt.</td>
                <td class="text-right"><?= $this->Number->currency($order->items_value_net, 'EUR'); ?></td>
            <tr style="">
                <td colspan="4">&nbsp;</td>
                <td class="text-right"><?= __d('shop', 'plus 20% Tax'); ?></td>
                <td class="text-right"><?= $this->Number->currency($order->order_value_tax, 'EUR'); ?></td>
            </tr>
            <tr style="font-weight: bold;">
                <td colspan="4" style="border-top: 2px solid #333; border-bottom: 2px solid #333;">&nbsp;</td>
                <td class="text-right" style="border-top: 2px solid #333; border-bottom: 2px solid #333;">Summe inkl. MwSt.</td>
                <td class="text-right" style="border-top: 2px solid #333; border-bottom: 2px solid #333;"><?= $this->Number->currency($order->items_value_taxed, 'EUR'); ?></td>
            </tr>
        <?php else: ?>
            <tr style="font-weight: bold; font-size: 133%;">
                <td colspan="4" style="border-top: 2px solid #333; border-bottom: 2px solid #333;">&nbsp;</td>
                <td class="text-right" style="border-top: 2px solid #333; border-bottom: 2px solid #333;">Gesamt</td>
                <td class="text-right" style="border-top: 2px solid #333; border-bottom: 2px solid #333;"><?= $this->Number->currency($order->items_value_taxed, 'EUR'); ?></td>
            </tr>
            <tr style="">
                <td colspan="4">&nbsp;</td>
                <td class="text-right"><?= __d('shop', 'includes 20% Tax'); ?></td>
                <td class="text-right"><?= $this->Number->currency($order->order_value_tax, 'EUR'); ?></td>
            </tr>
            </tr>
        <?php endif; ?>
    <?php endif; ?>
</table>