<?php $this->loadHelper('Number'); ?>
<?php $this->loadHelper('Banana.Status'); ?>
<div class="view">

    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <?= __d('shop', 'Order'); ?>  <?= h($entity->nr_formatted); ?>
                    <small class="pull-right"><?= __d('shop','Ordered') ?>: <?= h($entity->submitted); ?></small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info" style="margin: 1.3em 0;">
            <div class="col-sm-4 invoice-col">
                <strong><?= __d('shop', 'Billing address'); ?></strong>
                <?= $this->element('Shop.Admin/address', ['address' => $entity->billing_address]); ?>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <strong><?= __d('shop', 'Shipping address'); ?></strong>
                <?= $this->element('Shop.Admin/address', ['address' => $entity->shipping_address]); ?>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b><?= __d('shop','Order ID'); ?>:</b> <?= h($entity->nr_formatted); ?><br>
                <b><?= __d('shop','Order Date'); ?>:</b> <?= $this->Time->nice($entity->submitted); ?><br>
                <?php if ($entity->shop_customer): ?>
                <b><?= __d('shop','Customer'); ?>:</b> <?= $this->Html->link($entity->shop_customer->display_name,
                    [ 'controller' => 'ShopCustomers', 'action' => 'view', $entity->shop_customer_id]); ?>
                <?php endif; ?>
                <br /><br />
                <b><?= __d('shop','Status'); ?>:</b> <?= $this->Status->label($entity->status__status); ?>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- title row -->
        <div class="row">
            <!--
            <div class="col-xs-12">
                <h3 class="page-header">
                    <?= __d('shop', 'Order Items'); ?>
                    <small class="pull-right">Delivered: <?= h($entity->delivered); ?></small>
                </h3>
            </div>
            <!-- /.col -->
        </div>

        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <h3 class="page-header">
                    <?= __d('shop', 'Order Items'); ?>
                    <small class="pull-right"><?= $this->Number->currency($entity->order_value_total, $entity->currency); ?></small>
                </h3>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th><?= __d('shop','Pos'); ?></th>
                        <th><?= __d('shop','Qty'); ?></th>
                        <th><?= __d('shop','Serial'); ?></th>
                        <th><?= __d('shop','Product'); ?></th>
                        <th class="text-right"><?= __d('shop','Subtotal'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 0; ?>
                    <?php foreach ($entity->shop_order_items as $item): ?>
                        <tr>
                            <td><?= ++$i; ?></td>
                            <td><?= h($item->amount); ?></td>
                            <td><?= h($item->sku); ?></td>
                            <td><?= h($item->title); ?></td>
                            <td class="text-right"><?= $this->Number->currency($item->value_net, 'EUR'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">

            <!-- accepted payments column
            <div class="col-xs-6">
            </div>
             -->
            <!-- /.col -->
            <div class="col-xs-12">

                <div class="table-responsive">
                    <table class="table table-condensed">
                        <tbody>
                        <tr class="text-right">
                            <th style="width:50%"><?= __d('shop','Subtotal') ?>:</th>
                            <td><?= $this->Number->currency($entity->items_value_net, $entity->currency); ?></td>
                        </tr>
                        <tr class="text-right">
                            <th><?= __d('shop','Shipping') ?>:</th>
                            <td><?= $this->Number->currency($entity->shipping_value_net, $entity->currency); ?></td>
                        </tr>
                        <tr class="text-right">
                            <th><?= __d('shop','Tax') ?>:</th>
                            <td><?= $this->Number->currency($entity->items_value_tax, $entity->currency); ?></td>
                        </tr>
                        <tr class="text-right">
                            <th><?= __d('shop','Total') ?>:</th>
                            <td><?= $this->Number->currency($entity->items_value_taxed, $entity->currency); ?></td>
                        </tr>
                        <tr class="text-right">
                            <th><?= __d('shop','Discount') ?>:</th>
                            <td><?= $this->Number->currency($entity->coupon_value, $entity->currency); ?></td>
                        </tr>
                        <tr class="text-right">
                            <th><?= __d('shop','Order total') ?>:</th>
                            <td><?= $this->Number->currency($entity->order_value_total, $entity->currency); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row" style="margin-bottom: 2em;">
            <!-- accepted payments column -->
            <div class="col-xs-6">

                <h3 class="page-header">
                    <?= __d('shop', 'Payment'); ?>
                    <small class="pull-right"><?= __d('shop','Payed') ?>: <?= h($entity->payed); ?></small>
                </h3>

                <h4><?= __d('shop','Payment Type'); ?></h4>
                <p><?= h($entity->payment_type); ?></p>
                <?php
                $element = 'Shop.Admin/Payment/' . $entity->payment_type . '/order';
                if ($this->elementExists($element)) {
                    echo $this->element($element, ['order' => $entity]);
                } elseif (Cake\Core\Configure::read('debug')) {
                    echo __d('shop', 'Element missing: ' . $element);
                }
                ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-6">

                <h3 class="page-header">
                    <?= __d('shop', 'Shipping'); ?>
                    <small class="pull-right"><?= __d('shop', 'Delivered'); ?>: <?= h($entity->delivered); ?></small>
                </h3>

                <h4><?= __d('shop','Shipping Type'); ?></h4>
                <p><?= h($entity->shipping_type); ?></p>
                <?php
                $element = 'Shop.Admin/Shipping/' . $entity->shipping_type . '/order';
                if ($this->elementExists($element)) {
                    echo $this->element($element, ['order' => $entity]);
                } elseif (Cake\Core\Configure::read('debug')) {
                    echo __d('shop', 'Element missing: ' . $element);
                }
                ?>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-xs-12">
                <!--
                <?= $this->Button->link(__d('shop', 'Print'),
                    ['action' => 'printview', $entity->id, 'mode' => 'order'],
                    ['data-icon' => 'print', 'target' => '_blank', 'class' => 'pull-right']); ?>
                -->

                <?= $this->Button->link(__d('shop', 'Generate Order PDF'),
                    ['action' => 'pdfview', $entity->id, 'mode' => 'order'],
                    ['data-icon' => 'file-pdf-o', 'target' => '_blank', 'type' => 'primary', 'class' => '']); ?>

                <!--
                <?= $this->Button->link(__d('shop', 'Send Order confirmation'),
                    ['action' => 'sendorder', $entity->id, 'mode' => 'invoice'],
                    ['data-icon' => 'envelope-o']); ?>
                -->
            </div>
        </div>
    </section>

    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <?= __d('shop', 'Invoice'); ?> <?= h($entity->invoice_nr_formatted); ?>
                    <small class="pull-right"><?= __d('shop','Invoiced') ?>: <?= h($entity->invoiced); ?></small>
                </h2>
            </div>
            <!-- /.col -->
        </div>

        <?php if ($entity->invoice_nr): ?>
        <div class="row" style="margin-bottom: 2em;">
            <!-- accepted payments column -->
            <div class="col-xs-6">
                <b><?= __d('shop','Invoice #') ?>:</b> <?= h($entity->invoice_nr_formatted); ?><br>
                <b><?= __d('shop','Invoice date') ?>:</b> <?= h($entity->invoiced); ?><br>
            </div>
            <!-- /.col -->
            <div class="col-xs-6">
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <?php else: ?>
            <div class="row" style="margin-bottom: 2em;">
            <!-- accepted payments column -->
            <div class="col-xs-12">
                <p class="well"><?= __d('shop', 'No invoice'); ?></p>
            </div>
            <!-- /.col -->
        </div>
        <?php endif; ?>

        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-xs-12">
                <?php if ($entity->invoice_nr): ?>
                <!--
                <?= $this->Button->link(__d('shop', 'Print'),
                    ['action' => 'printview', $entity->id, 'mode' => 'invoice'],
                    ['data-icon' => 'print', 'target' => '_blank', 'class' => 'pull-right']); ?>
                -->

                <?= $this->Button->link(__d('shop', 'Generate Invoice PDF'),
                    ['action' => 'pdfview', $entity->id, 'mode' => 'invoice'],
                    ['data-icon' => 'file-pdf-o', 'target' => '_blank', 'type' => 'primary']); ?>

                <!--
                <?= $this->Button->link(__d('shop', 'Send Invoice'),
                    ['action' => 'sendinvoice', $entity->id, 'mode' => 'invoice'],
                    ['data-icon' => 'envelope-o']); ?>
                -->

                <?php elseif (!$entity->invoice_nr && $entity->getStatus() >= \Shop\Model\Table\ShopOrdersTable::ORDER_STATUS_CONFIRMED): ?>
                <?= $this->Button->link(__d('shop', 'Create invoice'),
                    ['action' => 'invoice', $entity->id],
                    ['data-icon' => 'refresh']); ?>

                <?php endif; ?>

                <?php if ($entity->getStatus() < \Shop\Model\Table\ShopOrdersTable::ORDER_STATUS_PAYED): ?>
                    <?= $this->Button->link(__d('shop', 'Mark PAYED'),
                        ['action' => 'payed', $entity->id],
                        ['data-icon' => 'money']); ?>

                <?php endif; ?>

            </div>
        </div>
    </section>

</div>