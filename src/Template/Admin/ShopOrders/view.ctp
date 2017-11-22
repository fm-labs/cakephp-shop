<?php $this->loadHelper('Number'); ?>
<?php $this->loadHelper('Banana.Status'); ?>
<?php $this->extend('Backend./Admin/Action/view'); ?>
<div class="view">

    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <?= __d('shop', 'Order'); ?>  <?= h($entity->nr_formatted); ?>
                    <small class="pull-right">Ordered: <?= h($entity->submitted); ?></small>
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
                <b>Order ID:</b> <?= h($entity->nr_formatted); ?><br>
                <b>Order Date:</b> <?= $this->Time->nice($entity->submitted); ?><br>
                <b>Account:</b> <?= h($entity->shop_customer_id); ?>
                <br /><br />
                <b>Status:</b> <?= $this->Status->label($entity->status__status); ?>
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
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Pos</th>
                        <th>Qty</th>
                        <th>Serial #</th>
                        <th>Product</th>
                        <th class="text-right">Subtotal</th>
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
            <!-- accepted payments column -->
            <div class="col-xs-6">
            </div>
            <!-- /.col -->
            <div class="col-xs-6">
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <tbody>
                        <tr class="text-right">
                            <th style="width:50%">Subtotal:</th>
                            <td><?= $this->Number->currency($entity->items_value_net, $entity->currency); ?></td>
                        </tr>
                        <tr class="text-right">
                            <th>Shipping:</th>
                            <td><?= $this->Number->currency($entity->shipping_value_net, $entity->currency); ?></td>
                        </tr>
                        <tr class="text-right">
                            <th>Tax:</th>
                            <td><?= $this->Number->currency($entity->items_value_tax, $entity->currency); ?></td>
                        </tr>
                        <tr class="text-right">
                            <th>Total:</th>
                            <td><?= $this->Number->currency($entity->items_value_total, $entity->currency); ?></td>
                        </tr>
                        <tr class="text-right">
                            <th>Voucher / Coupons:</th>
                            <td><?= $this->Number->currency($entity->coupon_value, $entity->currency); ?></td>
                        </tr>
                        <tr class="text-right">
                            <th>Final:</th>
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
                    <small class="pull-right">Payed: <?= h($entity->delivered); ?></small>
                </h3>

                [<?= h($entity->payment_type); ?>]
                <strong>UNPAID</strong>
                <br />

                <?php
                $element = 'Shop.Admin/Payment/' . $entity->payment_type . '/order';
                if ($this->elementExists($element)) {
                    echo $this->element($element, ['order' => $entity]);
                } else {
                    echo __d('shop', 'No payment type selected');
                }
                ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-6">

                <h3 class="page-header">
                    <?= __d('shop', 'Shipping'); ?>
                    <small class="pull-right">Delivered: <?= h($entity->delivered); ?></small>
                </h3>

                [<?= h($entity->shipping_type); ?>]
                <strong>UNSHIPPED</strong>
                <br />

                <?php
                $element = 'Shop.Admin/Shipping/' . $entity->shipping_type . '/order';
                if ($this->elementExists($element)) {
                    echo $this->element($element, ['order' => $entity]);
                } else {
                    echo __d('shop', 'No shipping type selected');
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

                <?= $this->Button->link(__d('shop', 'Send Order confirmation'),
                    ['action' => 'sendorder', $entity->id, 'mode' => 'invoice'],
                    ['data-icon' => 'envelope-o']); ?>
            </div>
        </div>
    </section>

    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <?= __d('shop', 'Invoice'); ?> <?= h($entity->invoice_nr_formatted); ?>
                    <small class="pull-right">Invoiced: <?= h($entity->invoiced); ?></small>
                </h2>
            </div>
            <!-- /.col -->
        </div>

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-6">
                <b>Invoice #:</b> <?= h($entity->invoice_nr_formatted); ?><br>
                <b>Invoice Date:</b> <?= h($entity->invoiced); ?><br>
            </div>
            <!-- /.col -->
            <div class="col-xs-6">
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-xs-12">
                <!--
                <?= $this->Button->link(__d('shop', 'Print'),
                    ['action' => 'printview', $entity->id, 'mode' => 'invoice'],
                    ['data-icon' => 'print', 'target' => '_blank', 'class' => 'pull-right']); ?>
                -->

                <?= $this->Button->link(__d('shop', 'Generate Invoice PDF'),
                    ['action' => 'pdfview', $entity->id, 'mode' => 'invoice'],
                    ['data-icon' => 'file-pdf-o', 'target' => '_blank', 'type' => 'primary']); ?>

                <?= $this->Button->link(__d('shop', 'Send Invoice'),
                    ['action' => 'sendinvoice', $entity->id, 'mode' => 'invoice'],
                    ['data-icon' => 'envelope-o']); ?>

            </div>
        </div>
    </section>

</div>