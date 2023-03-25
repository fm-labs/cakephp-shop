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

        <?= $this->element('Shop.Order/pdf/order_items_table', [
            'order' => $this->get('shopOrder'),
        ]); ?>
        <?= $this->element('Shop.Order/pdf/summary_table', [
            'order' => $this->get('shopOrder'),
            'calculator' => $this->get('calculator'),
        ]); ?>

    </div>

    <div>
        <p style="text-align:center;font-size: 90%;">Rechnungsdatum = Liefer- bzw. Leistungsdatum</p>

        <?= $this->element('Shop.Order/reverse_charge'); ?>

        <p style="text-align:center;font-weight: bold; font-size:90%">Danke für Ihr Vertrauen - für weitere Aufträge stehen wir gerne zur Verfügung!</p>
    </div>

</div>