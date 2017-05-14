Sehr geehrte/r <?= $order->billing_first_name ?> <?= $order->billing_last_name ?>,


vielen Dank für Ihre Bestellung!

--------------------------------


<?php
$paymentMap = ['payment_slip' => 'Rechnung mit Erlagschein', 'credit_card_internal' => 'Kreditkarte'];
?>

Zahlungsart: <?= $paymentMap[$order->payment_type]; ?>

Vorname: <?= $order->billing_first_name ?>

Zuname: <?= $order->billing_last_name ?>

Telefon: <?= $order->customer_phone ?>

Fax: <?= $order->customer_fax ?>

Email: <?= $order->shop_customer->email ?>

Strasse: <?= $order->billing_street ?>

PLZ: <?= $order->billing_zipcode ?>

Ort: <?= $order->billing_city ?>

Land: <?= $order->billing_country ?>



<?php foreach ($order->shop_order_items as $orderItem): ?>
    <?php echo sprintf("%s %s %s, %s , EUR %s\n\n",
        $orderItem->amount,
        $orderItem->unit,
        ($orderItem->ref) ? $orderItem->ref->sku : '-',
        $orderItem->title,
        number_format($orderItem->value_total, 2)
    ); ?>
<?php endforeach; ?>

Rechnungsbetrag: EUR <?= number_format($order->order_value_total, 2) ?>
