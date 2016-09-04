<?php $this->loadHelper('Backend.Ui'); ?>
<?php $this->assign('title', __d('shop', 'Cart')); ?>
<?php $this->Html->meta('robots', 'noindex,nofollow', ['block' => true]); ?>
<div class="shop cart index">
    <h1>Warenkorb</h1>

    <?php if (isset($cart->order)): ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="number"><?= __d('shop','Amount'); ?></th>
                <th><?= __d('shop','Product'); ?></th>
                <th class="number currency"><?= __d('shop','Item Price'); ?></th>
                <th class="number currency"><?= __d('shop','Price'); ?></th>
            </tr>
            </thead>
            <?php foreach ($cart->order->shop_order_items as $item): ?>
            <tr>
                <td class="number">
                    <?= $this->Form->create($item, ['url' => ['action' => 'update', $item->shop_order_id, $item->id]]); ?>
                    <?= $this->Form->hidden('id'); ?>
                    <?= $this->Form->hidden('shop_order_id'); ?>
                    <?= $this->Form->text('amount', ['type' => 'number', 'class' => 'amount', 'min' => 1, 'max' => 1000, 'step' => 1]); ?>
                    <?= h($item->unit); ?>
                    <?= $this->Form->end(); ?>
                </td>
                <td class="title"><?= h($item->title); ?><br />
                    <?= $this->Ui->link(__d('shop','Remove from cart'), ['action' => 'remove', $cart->order->id, $item->id], ['icon' => 'trash']); ?></td>
                <td class="number currency"><?= $this->Number->currency($item->item_value_taxed, 'EUR'); ?></td>
                <td class="number currency"><?= $this->Number->currency($item->value_total, 'EUR'); ?></td>
            </tr>
            <?php endforeach; ?>
            <tr style="font-weight: bold;">
                <td>&nbsp;</td>
                <td>Gesamt</td>
                <td>&nbsp;</td>
                <td class="currency"><?= $this->Number->currency($cart->order->items_value_taxed, 'EUR'); ?></td>
            </tr>
        </table>

        <div class="actions" style="text-align: right;">
            <?= '' //$this->Ui->link(__d('shop','Refresh'), ['controller' => 'Cart', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>&nbsp;&nbsp;
            <?= $this->Ui->link(__d('shop','Continue Shopping'), ['controller' => 'Catalogue', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>&nbsp;&nbsp;
            <?= $this->Ui->link(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>
        </div>

        <div class="ui divider"></div>
        <?= $this->element('Shop.Cart/customer_info'); ?>


    <?php else: ?>
        <h2><?= h(__d('shop', 'No items in cart')); ?></h2>
        <div class="actions" style="">
            <?= $this->Ui->link(__d('shop','Continue Shopping'),['controller' => 'Catalogue', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>&nbsp;&nbsp;
        </div>
    <?php endif; ?>
    <?php debug($cart); ?>
    <?php debug($this->request->session()->read('Shop')); ?>
</div>