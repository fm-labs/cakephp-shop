<?php
$this->loadHelper('Number');
$order = $this->get('order');
?>
<table class="table table-striped">
    <thead>
    <tr>
        <th style="width: 20px;"><?= __d('shop','Pos'); ?></th>
        <th style="width: 100px;" class="text-end"><?= __d('shop','Amount'); ?></th>
        <th style="width: 20px;">&nbsp;</th>
        <th><?= __d('shop','Product'); ?></th>
        <th class="text-end"><?= __d('shop','Item Price'); ?></th>
        <th class="text-end"><?= __d('shop','Price'); ?></th>
    </tr>
    </thead>
    <?php if ($order && $order->shop_order_items): ?>
        <?php foreach ($order->shop_order_items as $idx => $item): ?>
            <tr>
                <td><?= h($idx+1) ?></td>
                <td class="text-end"><?= h($item->amount) ?></td>
                <td><?= h($item->unit); ?></td>
                <td><?= h($item->title); ?></td>
                <td class="text-end"><?= $this->Number->currency($item->item_value_display, 'EUR'); ?></td>
                <td class="text-end"><?= $this->Number->currency($item->value_display, 'EUR'); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>