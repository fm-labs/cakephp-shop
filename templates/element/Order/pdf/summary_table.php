<?php
/** @var \Shop\Model\Entity\ShopOrder $order */
/** @var \Shop\Core\Order\CostCalculator $calculator */

if (!isset($calculator)) {
    echo $this->Html->div('alert alert-warning', "Calculator not provided");
    return;
}
if (!isset($order)) {
    echo $this->Html->div('alert alert-warning', "Order not Provider");
    return;
}

$getValueFormatted = function(\Shop\Core\Order\CostValueInterface $value) {
    $_value = $value->getTotalValue();
    if (\Cake\Core\Configure::read('Shop.Price.displayNet')) {
        $_value = $value->getNetValue();
    }
    return $this->Number->currency($_value, 'EUR');
}
?>
<table style="width:180mm; margin-bottom: 10mm;" cellpadding="5" cellspacing="0">

    <tr style="font-weight: bold; border-top: 2px solid #333;">
        <td class="currency text-start"><?= __d('shop', 'Order Items total'); ?></td>
        <td style="text-align:right;"><?= $getValueFormatted($calculator->getValue('order_items')); ?></td>
    </tr>
    <tr style="">
        <td class="currency text-start"><?= __d('shop', 'Shipping'); ?></td>
        <td style="text-align:right;"><?= $getValueFormatted($calculator->getValue('shipping')); ?></td>
    </tr>

    <?php if ($calculator->getValue('coupon') && $calculator->getValue('coupon')->getTotalValue() != 0): ?>
        <tr style="">
            <td class="currency text-start"><?= __d('shop', 'Coupon discount'); ?></td>
            <td style="text-align:right;"><?= $getValueFormatted($calculator->getValue('coupon')); ?></td>
        </tr>
    <?php endif; ?>


    <?php if (\Cake\Core\Configure::read('Shop.Price.displayNet')): ?>
        <tr style="font-weight: bold; border-top: 2px solid #333;">
            <td class="currency text-start"><?= __d('shop', 'Subtotal excl. tax'); ?></td>
            <td style="text-align:right;"><?= $this->Number->currency($calculator->getNetValue(), 'EUR'); ?></td>
        </tr>
        <?php foreach ($calculator->getTaxes() as $tax): ?>
            <?php if ($tax['value'] == 0): continue; endif; ?>
            <tr style="">
                <td class="currency text-start"><?= __d('shop', 'VAT {0}%', $tax['taxRate']); ?></td>
                <td style="text-align:right;"><?= $this->Number->currency($tax['value'], 'EUR'); ?></td>
            </tr>
        <?php endforeach; ?>
        <tr style="font-weight: bold; border-top: 2px solid #333;">
            <td class="currency text-start"><?= __d('shop', 'Total incl. VAT'); ?></td>
            <td style="text-align:right;"><?= $this->Number->currency($calculator->getTotalValue(), 'EUR'); ?></td>
        </tr>
    <?php else: ?>
        <tr style="font-weight: bold; border-top: 2px solid #333;">
            <td class="currency text-start"><?= __d('shop', 'Total'); ?></td>
            <td style="text-align:right;"><?= $this->Number->currency($calculator->getTotalValue(), 'EUR'); ?></td>
        </tr>
        <?php foreach ($calculator->getTaxes() as $tax): ?>
            <?php if ($tax['value'] == 0): continue; endif; ?>
            <tr style="">
                <td class="currency text-start"><?= __d('shop', 'Incl. VAT {0}%', $tax['taxRate']); ?></td>
                <td style="text-align:right;"><?= $this->Number->currency($tax['value'], 'EUR'); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>