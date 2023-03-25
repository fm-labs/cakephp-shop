<?php
use Cake\Core\Configure;

/** @var \Shop\Model\Entity\ShopOrder $shopOrder */
$shopOrder = $this->get('shopOrder');
$billingAddress = $shopOrder->getBillingAddress();
$mode = $this->get('mode');
?>
<div class="view">
    <div class="print_sender">
<?php echo Configure::read('Shop.Owner.name'); ?><br />
<?php echo Configure::read('Shop.Owner.street1'); ?><br />
<?php echo sprintf("%s %s",Configure::read('Shop.Owner.zipcode'),Configure::read('Shop.Owner.city')); ?><br />
<?php echo Configure::read('Shop.Owner.country'); ?><br />
<?php echo h(Configure::read('Shop.Owner.taxId')); ?>
<br />
<br />
    </div>

    <div class="print_recipient">
<?php if ($billingAddress['company_name']): ?>
<?php echo $billingAddress['company_name']; ?><br />
<?php endif; ?>
<?php echo $billingAddress['first_name']; ?>&nbsp;<?php echo $billingAddress['last_name']; ?><br />
<?php echo $billingAddress['street']; ?><br />
<?php if ($billingAddress['street2']): ?>
    <?php echo $billingAddress['street2']; ?><br />
<?php endif; ?>
<?php echo sprintf("%s %s", $billingAddress['zipcode'], $billingAddress['city']); ?><br />
<?php echo $billingAddress->relcountry['name_de']; ?><br />
<?php if ($billingAddress['taxid']): ?>
<?php echo h($billingAddress['taxid']); ?><br />
<?php endif; ?>
    </div>


    <div class="print_date" style="text-align:right;">
        <?php echo __d('shop', 'Date of order: {0}', $shopOrder->submitted->format("d.m.Y"));?>
        <?php if ($mode == "invoice" && $shopOrder->submitted):?>
            <br />
            <?php
            echo __d('shop', 'Rechnungsdatum: {0}', $shopOrder->submitted->format("d.m.Y"));
            ?>
        <?php endif; ?>
        <br />
    </div>
    <div class="print_nr" style="font-weight:bold; font-size: 120%;">
        <?php echo ($mode == "order")
            ? __d('shop', "Order: {0}", $shopOrder->nr_formatted)
            : __d('shop', "Invoice: {0}", $shopOrder->invoice_nr_formatted); ?>
    </div>
    <div class="print_items">
        <table style="width:180mm; margin-bottom: 10mm;" cellpadding="5" cellspacing="0">
            <tr>
                <th style="width:10mm; background-color:#CCC; border-bottom:1px solid #000;"><?php
                    echo __d('shop', 'Nr.'); ?></th>
                <th style="width:15mm; background-color:#CCC; border-bottom:1px solid #000;"><?php
                    echo __d('shop', 'Menge'); ?></th>
                <th style="width:100mm; background-color:#CCC; border-bottom:1px solid #000;"><?php
                    echo __d('shop', 'Bezeichnung'); ?></th>
                <th style="width:25mm;text-align:right; background-color:#CCC; border-bottom:1px solid #000;"><?php
                    echo __d('shop', 'Preis'); ?></th>
                <th style="width:30mm;text-align:right; background-color:#CCC; border-bottom:1px solid #000;"><?php
                    echo __d('shop', 'Betrag'); ?></th>
            </tr>
            <?php
            $i = 0;
            foreach ($shopOrder->shop_order_items as $item):
                ?>
                <tr>
                    <td><?php echo ++$i; ?></td>
                    <td><?php echo $item->amount; ?>x</td>
                    <td><?php echo $item->title; ?></td>
                    <td style="text-align:right;"><?php echo $this->Number->currency($item->item_value_net, 'EUR'); ?></td>
                    <td style="text-align:right;"><?php echo $this->Number->currency($item->value_net, 'EUR'); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:right;font-weight:bold"><?php echo __d('shop', 'Summe exkl. MwSt.'); ?></td>
                <td style="text-align:right;font-weight:bold"><?php echo $this->Number->currency($shopOrder->items_value_net, 'EUR'); ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:right;"><?php echo __d('shop', '{0}% MwSt.', 20); ?></td>
                <td style="text-align:right;"><?php echo $this->Number->currency($shopOrder->items_value_tax, 'EUR'); ?></td>
            </tr>
            <?php if ($shopOrder['coupon_value'] > 0):?>
                <tr>
                    <td colspan="4" style="text-align:right;font-weight:bold"><?php echo __d('shop', 'Summe inkl. MwSt.'); ?></td>
                    <td style="text-align:right;font-weight:bold;"><?php echo $this->Number->currency($shopOrder->items_value_taxed, 'EUR'); ?></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:right;"><?php echo __d('shop', 'Gutschein Rabatt'); ?></td>
                    <td style="text-align:right;">- <?php echo $this->Number->currency($shopOrder['coupon_value'], 'EUR'); ?></td>
                </tr>
            <?php endif;?>
            <tr>
                <td colspan="4" style="text-align:right;font-weight:bold;"><?php echo __d('shop', 'Rechnungsbetrag'); ?></td>
                <td style="text-align:right;font-weight:bold"><?php echo $this->Number->currency($shopOrder->order_value_total, 'EUR'); ?></td>
            </tr>
        </table>
    </div>

    <div>
        <p style="text-align:center;font-size: 90%;">Rechnungsdatum = Liefer- bzw. Leistungsdatum</p>

        <?= $this->element('Shop.Order/reverse_charge'); ?>

        <p style="text-align:center;font-weight: bold; font-size:90%">Danke für Ihr Vertrauen - für weitere Aufträge stehen wir gerne zur Verfügung!</p>
    </div>

</div>