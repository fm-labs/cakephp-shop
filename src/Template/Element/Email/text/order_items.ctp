<?php
if (!isset($items)) return false;
?>
<?php foreach ($items as $orderItem): ?>
    <?php echo sprintf("%s\t%s\t%s,\t%s,\tEUR %s\n\n",
        $orderItem->amount,
        $orderItem->unit,
        ($orderItem->ref) ? $orderItem->ref->sku : '-',
        $orderItem->title,
        number_format($orderItem->value_total, 2)
    ); ?>
<?php endforeach; ?>