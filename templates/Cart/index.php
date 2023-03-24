<?php

$this->loadHelper('Bootstrap.Ui');
$this->loadHelper('Media.Media');
$this->Html->meta('robots', 'noindex,nofollow', ['block' => true]);
$this->Breadcrumbs->add(__d('shop', 'Shop'), ['_name' => 'shop:index']);
$this->Breadcrumbs->add(__d('shop', 'Cart'), ['action' => 'index']);
$this->assign('title', __d('shop', 'Cart'));

/** @var \Shop\Model\Entity\ShopOrder $cart */
$cart = $this->get('cart');

/** @var \Shop\Core\Order\CostCalculator $calculator */
$calculator = $this->get('calculator');
?>
<div class="shop cart index container">

    <h1><?= __d('shop', 'Your shopping cart'); ?></h1>

    <?= $this->Form->create(null, ['url' => ['action' => 'cartUpdate', $cart->id]]); ?>
    <?= $this->Form->hidden('id', ['value' => $cart->id]); ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th colspan="2"><?= __d('shop', 'Product'); ?></th>
            <th class="number"><?= __d('shop', 'Amount'); ?></th>
            <th class="number currency text-end"><?= __d('shop', 'Item Price'); ?></th>
            <th class="number currency text-end"><?= __d('shop', 'Total'); ?></th>
        </tr>
        </thead>
        <?php foreach ($cart->shop_order_items as $item): ?>
        <?php /** @var \Shop\Model\Entity\ShopOrderItem $item */ ?>
            <tr>
                <?php if ($item->getProduct()->featured_image_file): ?>
                    <td class="image" style="width: 50px;">
                        <?php echo $this->Media->thumbnail($item->getProduct()->featured_image_file->filepath, ['width' => 45, 'height' => 45]); ?>
                    </td>
                <?php else: ?>
                    <td>&nbsp;</td>
                <?php endif; ?>
                <td class="title">
                    <div class="cart-item-title">
                        <?= $this->Html->link($item->title, $item->getProduct()->url); ?>
                    </div>
                    <div class="cart-item-remove">
                        <?= $this->Html->link(
                            __d('shop', 'Remove from cart'),
                            ['action' => 'remove', $cart->id, $item->id],
                            ['data-icon' => 'trash', 'confirm' => __d('shop', 'Do you really want to remove this item from cart?')]
                        ); ?>
                    </div>
                </td>
                <td class="number">
                    <?= $this->Form->text('amount_' . $item->id, [
                        'type' => 'number',
                        'class' => 'amount',
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1,
                        'default' => $item->amount,
                        'style' => 'max-width: 75px;'
                    ]); ?>
                </td>
                <td class="number currency text-end"><?= $this->Number->currency($item->item_value_display, 'EUR'); ?></td>
                <td class="number currency text-end"><?= $this->Number->currency($item->value_display, 'EUR'); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php echo $this->element('Shop.Order/calculation_table', ['calculator' => $calculator, 'order' => $cart]) ?>

    <div class="actions" style="text-align: right;">
        <?= $this->Form->button(__d('shop', 'Update cart'), ['class' => 'btn btn-primary']); ?>&nbsp;&nbsp;
        <?= $this->Ui->link(__d('shop', 'Continue shopping'), ['controller' => 'Shop', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>
        &nbsp;&nbsp;
        <?= $this->Ui->link(__d('shop', 'Checkout'), ['controller' => 'Checkout', 'action' => 'index', $cart->cartid], ['class' => 'btn btn-primary']); ?>
    </div>

    <div class="ui divider"></div>
    <?= $this->element('Shop.Cart/customer_info'); ?>

    <?= $this->Form->end(); ?>


    <div class="shop-cart-coupon-container">
        <h3><?= __('Redeem coupon'); ?></h3>
        <?= $this->Form->create(null, [
            'url' => ['action' => 'addCoupon'],
            'horizontal' => false,
        ]); ?>
        <?= $this->Form->hidden('op', ['value' => 'cart_add_coupon']); ?>
        <?= $this->Form->hidden('cart_id', ['value' => $cart->cartid]); ?>
        <?= $this->Form->control('coupon_code', [
            'class' => 'w-50',
            'placeholder' => __('Enter coupon code here')
        ]); ?>
        <?= $this->Form->button(__d('shop', 'Redeem coupon'), ['class' => 'btn btn-primary']); ?>&nbsp;&nbsp;
        <?= $this->Form->end(); ?>
    </div>

</div>