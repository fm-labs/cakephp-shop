<?php
$ccBrands = [
    'visa' => 'Visa',
    'mastercard' => 'Mastercard',
    'dinersclub' => 'Diners Club'
];

$this->Form->context(new \Cake\View\Form\EntityContext($this->request, [
    'entity' => $order,
    'table' => 'Shop.ShopOrders'
]));
?>
<div class="payment-method" style="margin: -25px 0 0 10px;">
    <div class="payment-method-label">
        <span style="font-size: 1.2em;"><?= __d('shop','Invoice with payment slip'); ?></span>
    </div>
    <div class="payment-method-select">
        <p>Lieferung auf Rechnung mit Erlagschein.</p>
    </div>
</div>
<hr />