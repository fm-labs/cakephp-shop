<?php

$this->loadHelper('Bootstrap.Ui');
$this->loadHelper('Media.Media');
$this->Html->meta('robots', 'noindex,nofollow', ['block' => true]);
$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index']);
$this->Breadcrumbs->add(__d('shop','Cart'), ['action' => 'index']);
$this->assign('title', __d('shop', 'Cart'));

/** @var \Shop\Model\Entity\ShopOrder $cart */
$cart = $this->get('cart');
?>
<div class="shop cart index container">

    <h1><?= __d('shop','Your shopping cart'); ?></h1>

    <?= $this->Form->create(null, ['url' => ['action' => 'cartUpdate', $cart->id]]); ?>
    <?= $this->Form->hidden('id', ['value' => $cart->id]); ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th colspan="2"><?= __d('shop','Product'); ?></th>
            <th class="number"><?= __d('shop','Amount'); ?></th>
            <th class="number currency text-end"><?= __d('shop','Item Price'); ?></th>
            <th class="number currency text-end"><?= __d('shop','Total'); ?></th>
        </tr>
        </thead>
        <?php foreach ($cart->shop_order_items as $item): ?>
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
                        __d('shop','Remove from cart'),
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
        <tr style="font-weight: bold; font-size: 1.3em;">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="currency text-end"><?= __d('shop', 'Order Items total'); ?></td>
            <td class="currency text-end"><?= $this->Number->currency($cart->items_value_display, 'EUR'); ?></td>
        </tr>


        <?php if ($cart->coupon_code): ?>
            <tr style="">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="currency text-end">
                    <?= __d('shop', 'Coupon {0}', $cart->coupon_code); ?><br />
                    <?= $this->Html->link(__('Remove coupon'), ['controller' => 'Cart', 'action' => 'removeCoupon']); ?>
                </td>
                <td class="currency text-end"><?= $this->Number->currency($cart->coupon_value * -1, 'EUR'); ?></td>
            </tr>
            <tr style="font-weight: bold; font-size: 1.3em;">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="currency text-end"><?= __d('shop', 'Payable'); ?></td>
                <td class="currency text-end"><?= $this->Number->currency($cart->order_value_total - $cart->coupon_value, 'EUR'); ?></td>
            </tr>
        <?php endif; ?>


        <tr style="">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="currency text-end"><?= __d('shop', 'Order Tax'); ?></td>
            <td class="currency text-end"><?= $this->Number->currency($cart->order_value_tax, 'EUR'); ?></td>
        </tr>
        <tr style="font-weight: bold; font-size: 1.3em;">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="currency text-end"><?= __d('shop', 'Order total'); ?></td>
            <td class="currency text-end"><?= $this->Number->currency($cart->order_value_total, 'EUR'); ?></td>
        </tr>
    </table>

    <div class="actions" style="text-align: right;">
        <?= $this->Form->button(__d('shop','Update cart'), ['class' => 'btn btn-primary']); ?>&nbsp;&nbsp;
        <?= $this->Ui->link(__d('shop','Continue shopping'), ['controller' => 'Shop', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>&nbsp;&nbsp;
        <?= $this->Ui->link(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', $cart->cartid], ['class' => 'btn btn-primary']); ?>
    </div>

    <div class="ui divider"></div>
    <?= $this->element('Shop.Cart/customer_info'); ?>

    <?= $this->Form->end(); ?>


    <?= $this->Form->create(null, [
            'url' => ['action' => 'addCoupon']
    ]); ?>
    <?= $this->Form->hidden('op', ['value' => 'cart_add_coupon']); ?>
    <?= $this->Form->hidden('cart_id', ['value' => $cart->cartid]); ?>
    <?= $this->Form->control('coupon_code'); ?>
    <?= $this->Form->button(__d('shop','Redeem coupon'), ['class' => 'btn btn-primary']); ?>&nbsp;&nbsp;
    <?= $this->Form->end(); ?>

    <hr />
    <?php if (isset($calculator)): ?>
        <?php echo $this->element('Shop.Cart/calculation_table', ['calculator' => $calculator]) ?>
        <hr />
        <?php echo $this->element('Shop.Order/cost_calculator', ['calculator' => $calculator]) ?>
    <?php endif; ?>


    <?php debug($cart); ?>
    <?php debug($this->request->getSession()->read('Shop')); ?>
</div>