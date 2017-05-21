Es ist eine Bestellung aus dem Online-Shop eingelangt:


Auftraggeber:
-----------------
<?= $this->element('Shop.Email/text/customer', ['customer' => $order->shop_customer]); ?>


Rechnungsadresse:
-----------------
<?= $this->element('Shop.Email/text/address', ['address' => $order->getBillingAddress()]); ?>


Zahlung:
-----------------
<?= $this->element('Shop.Email/text/order_payment_merchant', ['order' => $order]); ?>


Lieferadresse:
--------------
<?= $this->element('Shop.Email/text/address', ['address' => $order->getShippingAddress()]); ?>


Zusätzliche Informationen des Kunden:
-------------------------------------

<?= $order->customer_notes; ?>


Email: <?= $order->customer_email; ?>

Telefon: <?= $order->customer_phone; ?>



Bestellartikel:
---------------
<?= $this->element('Shop.Email/text/order_items', ['items' => $order->shop_order_items]); ?>



Rechnungsbetrag: EUR <?= number_format($order->order_value_total, 2) ?>
