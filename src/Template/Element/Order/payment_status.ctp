<?php
/**
 * Payment Info / Status
 */
?>
<?php if ($order->status < \Shop\Model\Table\ShopOrdersTable::ORDER_STATUS_PAYED): ?>
    <div class="alert alert-warning">
        <div class="row">
            <div class="col-md-6">
                <strong><?= __d('shop','Payment status: UNPAYED'); ?></strong>

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