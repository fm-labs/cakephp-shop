<?php
/** @var \Shop\Core\Order\CostCalculator $calculator */
$calculator = $this->get('calculator');

/** @var \Shop\Model\Entity\ShopOrder $order */
$order = $this->get('order');

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
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="currency text-end"><?= __d('shop', 'Order Items total'); ?></td>
            <td class="currency text-end"><?= $getValueFormatted($calculator->getValue('order_items')); ?></td>
        </tr>
        <tr style="">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="currency text-end"><?= __d('shop', 'Shipping'); ?></td>
            <td class="currency text-end"><?= $getValueFormatted($calculator->getValue('shipping')); ?></td>
        </tr>
        <tr style="">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="currency text-end"><?= __d('shop', 'Coupon discount'); ?></td>
            <td class="currency text-end"><?= $getValueFormatted($calculator->getValue('coupon')); ?></td>
        </tr>


        <?php if (\Cake\Core\Configure::read('Shop.Price.displayNet')): ?>
        <tr class ="fw-bold" style="border-top: 2px solid #333;">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="currency text-end"><?= __d('shop', 'Net Total'); ?></td>
            <td class="currency text-end"><?= $this->Number->currency($calculator->getNetValue(), 'EUR'); ?></td>
        </tr>
        <?php foreach ($calculator->getTaxes() as $tax): ?>
            <tr style="">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="currency text-end"><?= __d('shop', 'Tax {0}%', $tax['taxRate']); ?></td>
                <td class="currency text-end"><?= $this->Number->currency($tax['value'], 'EUR'); ?></td>
            </tr>
        <?php endforeach; ?>
            <tr class ="fw-bold" style="border-top: 2px solid #333;">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="currency text-end"><?= __d('shop', 'Total'); ?></td>
            <td class="currency text-end"><?= $this->Number->currency($calculator->getTotalValue(), 'EUR'); ?></td>
        </tr>
        <?php else: ?>
            <tr class ="fw-bold" style="border-top: 2px solid #333;">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="currency text-end"><?= __d('shop', 'Total'); ?></td>
                <td class="currency text-end"><?= $this->Number->currency($calculator->getTotalValue(), 'EUR'); ?></td>
            </tr>
            <?php foreach ($calculator->getTaxes() as $tax): ?>
            <tr style="">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="currency text-end"><?= __d('shop', 'Incl. Tax {0}%', $tax['taxRate']); ?></td>
                <td class="currency text-end"><?= $this->Number->currency($tax['value'], 'EUR'); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>
