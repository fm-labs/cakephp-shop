<?php
/** @var \Shop\Model\Entity\ShopOrder $order */
/** @var \Shop\Core\Order\CostCalculator $calculator */
//$calculator = $this->get('calculator');
//$order = $this->get('order');
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
<div>

    <table class="table table-striped">

        <tr class ="fw-bold" style="border-top: 2px solid #333;">
            <td class="currency text-start"><?= __d('shop', 'Order Items total'); ?></td>
            <td class="currency text-end"><?= $getValueFormatted($calculator->getValue('order_items')); ?></td>
        </tr>
        <tr style="">
            <td class="currency text-start"><?= __d('shop', 'Shipping'); ?></td>
            <td class="currency text-end"><?= $getValueFormatted($calculator->getValue('shipping')); ?></td>
        </tr>

        <?php if ($calculator->getValue('coupon') && $calculator->getValue('coupon')->getTotalValue() != 0): ?>
            <tr style="">
                <td class="currency text-start">
                    <?= __d('shop', 'Coupon discount'); ?>
                </td>
                <td class="currency text-end"><?= $getValueFormatted($calculator->getValue('coupon')); ?></td>
            </tr>
        <?php endif; ?>


        <?php if (\Cake\Core\Configure::read('Shop.Price.displayNet')): ?>
            <tr class ="fw-bold" style="border-top: 2px solid #333;">
                <td class="currency text-start"><?= __d('shop', 'Subtotal excl. VAT'); ?></td>
                <td class="currency text-end"><?= $this->Number->currency($calculator->getNetValue(), 'EUR'); ?></td>
            </tr>
            <?php foreach ($calculator->getTaxes() as $tax): ?>
                <?php if ($tax['value'] == 0): continue; endif; ?>
                <tr style="">
                    <td class="currency text-start"><?= __d('shop', 'VAT {0}%', $tax['taxRate']); ?></td>
                    <td class="currency text-end"><?= $this->Number->currency($tax['value'], 'EUR'); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class ="fw-bold" style="border-top: 2px solid #333;">
                <td class="currency text-start"><?= __d('shop', 'Total incl. VAT'); ?></td>
                <td class="currency text-end"><?= $this->Number->currency($calculator->getTotalValue(), 'EUR'); ?></td>
            </tr>
        <?php else: ?>
            <tr class ="fw-bold" style="border-top: 2px solid #333;">
                <td class="currency text-start"><?= __d('shop', 'Total'); ?></td>
                <td class="currency text-end"><?= $this->Number->currency($calculator->getTotalValue(), 'EUR'); ?></td>
            </tr>
            <?php foreach ($calculator->getTaxes() as $tax): ?>
                <?php if ($tax['value'] == 0): continue; endif; ?>
                <tr style="">
                    <td class="currency text-start"><?= __d('shop', 'Incl. VAT {0}%', $tax['taxRate']); ?></td>
                    <td class="currency text-end"><?= $this->Number->currency($tax['value'], 'EUR'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>
