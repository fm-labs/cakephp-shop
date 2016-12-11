<?php $this->loadHelper('Bootstrap.Ui'); ?>
<?php //$this->extend('Shop.Checkout/base'); ?>
<?php //$this->assign('heading', __d('shop','Helllo')); ?>
<div class="shop checkout step index">
    <h1>Ihre Bestellung</h1>

    <ul>
        <?php foreach ($steps as $step => $stepUrl): ?>
        <li><?= $this->Html->link($step, $stepUrl, []); ?></li>
        <?php endforeach; ?>
    </ul>

    <?php if (empty($order->shop_order_items)): ?>
        <h2><?= __d('shop','No items in cart'); ?></h2>
        <div class="ui divider"></div>
        <div class="actions" style="text-align: right;">
            <?= $this->Html->link(__d('shop','Continue shopping'), ['controller' => 'Catalogue', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>
        </div>

        <?php else: ?>
        <?= $this->element('Shop.Checkout/cart'); ?>
        <?= $this->element('Shop.Cart/customer_info'); ?>

        <div class="ui actions" style="text-align: right;">
            <?= $this->Html->link(__d('shop','Continue'), ['action' => 'next'], ['class' => 'ui primary button']); ?>
        </div>

    <?php endif; ?>



</div>