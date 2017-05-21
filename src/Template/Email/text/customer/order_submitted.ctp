Sehr geehrte/r <?= $order->shop_customer->display_name ?>,


vielen Dank für Ihre Bestellung!

--------------------------------


Bestellartikel:
-----------------
<?= $this->element('Shop.Email/text/order_items', ['items' => $order->shop_order_items]); ?>


Rechnungsbetrag: EUR <?= number_format($order->order_value_total, 2) ?>



Rechnungsadresse:
-----------------
<?= $this->element('Shop.Email/text/address', ['address' => $order->getBillingAddress()]); ?>


Zahlung:
-----------------
<?= $this->element('Shop.Email/text/order_payment_customer', ['order' => $order]); ?>


Lieferadresse:
--------------
<?= $this->element('Shop.Email/text/address', ['address' => $order->getShippingAddress()]); ?>


Zusätzliche Informationen für den Verkäufer:
-------------------------------------

<?= $order->customer_notes; ?>


Email: <?= $order->customer_email; ?>

Telefon: <?= $order->customer_phone; ?>



<?= $this->element('Shop.Email/text/order_custom'); ?>