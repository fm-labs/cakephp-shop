Es ist eine Bestellung aus dem Online-Shop eingelangt:


Anrede:
Vorname: <?= $order->getBillingAddress()->first_name ?>

Zuname: <?= $order->getBillingAddress()->last_name ?>

Auftraggeber:
-------------

Email: <?= $order->shop_customer->email ?>

Strasse: <?= $order->getBillingAddress()->street ?>

PLZ: <?= $order->getBillingAddress()->zipcode ?>

Ort: <?= $order->getBillingAddress()->city ?>

Land: <?= $order->getBillingAddress()->country ?>



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
