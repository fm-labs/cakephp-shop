<?php
/** @var \Shop\Model\Entity\ShopOrder $order */
/** @var \Shop\Core\Order\CostCalculator $calculator */
//$order = $this->get('order');
//$calculator = $this->get('calculator');
?>
<div>
    <?php echo $this->element('Shop.Order/order_items_table', [
        'order' => $order
    ]) ?>
    <?php echo $this->element('Shop.Order/calculation_table', [
        'calculator' => $calculator,
        'order' => $order
    ]) ?>
</div>