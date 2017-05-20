<?php
/**
 * Payment Info / Status
 * Show provider specific 'pay' element, if any
 */
$element = 'Shop.Payment/' . $order->payment_type . '/pay';
if ($this->elementExists($element)) {
    //$this->element($element);
    //return;
}
?>
<?php if ($order->status == \Shop\Model\Table\ShopOrdersTable::ORDER_STATUS_PAYED): ?>
    &nbsp;
<?php elseif ($order->payment_type === "credit_card_internal") : ?>

<?php elseif ($order->payment_type === "payment_slip") : ?>
<?php elseif ($order->status == \Shop\Model\Table\ShopOrdersTable::ORDER_STATUS_CONFIRMED): ?>
<div class="alert alert-success">
    <strong><?= __('We are processing your payment'); ?></strong>
</div>

<?php elseif ($order->status < \Shop\Model\Table\ShopOrdersTable::ORDER_STATUS_PAYED) : ?>
<div class="alert alert-warning">
    <div class="row">
        <div class="col-md-6">
            <h3 style="margin: 0; padding: 0;"><?= __d('shop','Payment status: UNPAYED'); ?></h3>
        </div>
        <div class="col-md-6 text-right">
            <?= $this->Html->link(__d('shop', 'Go to payment page'),
                ['controller' => 'Payment', 'action' => 'index', $order->uuid],
                ['class' => 'btn btn-primary']
            ); ?>
        </div>
    </div>
</div>
<?php endif; ?>