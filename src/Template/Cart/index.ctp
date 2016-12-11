<?php $this->loadHelper('Bootstrap.Ui'); ?>
<?php $this->Html->meta('robots', 'noindex,nofollow', ['block' => true]); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Cart'), ['action' => 'index']); ?>
<?php $this->assign('title', __d('shop', 'Cart')); ?>
<div class="shop cart index">
    <h1>Warenkorb</h1>

    <?php if ($order && count($order->shop_order_items) > 0): ?>
        <?= $this->Form->create(null, ['url' => ['action' => 'cart_update', $order->id]]); ?>
        <?= $this->Form->hidden('id', ['value' => $order->id]); ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="number" style="width: 100px;"><?= __d('shop','Amount'); ?></th>
                <th style="width: 20px;">&nbsp;</th>
                <th><?= __d('shop','Product'); ?></th>
                <th class="number currency"><?= __d('shop','Item Price'); ?></th>
                <th class="number currency"><?= __d('shop','Price'); ?></th>
            </tr>
            </thead>
            <?php foreach ($order->shop_order_items as $item): ?>
            <tr>
                <td class="number">
                    <?= $this->Form->text('amount_' . $item->id, [
                        'type' => 'number',
                        'class' => 'amount',
                        'min' => 1,
                        'max' => 1000,
                        'step' => 1,
                        'default' => $item->amount
                    ]); ?>
                </td>
                <td><?= h($item->unit); ?></td>
                <td class="title"><?= h($item->title); ?><br />
                    <?= $this->Ui->link(__d('shop','Remove from cart'), ['action' => 'remove', $order->id, $item->id], ['data-icon' => 'trash']); ?></td>
                <td class="number currency"><?= $this->Number->currency($item->item_value_taxed, 'EUR'); ?></td>
                <td class="number currency"><?= $this->Number->currency($item->value_total, 'EUR'); ?></td>
            </tr>
            <?php endforeach; ?>
            <tr style="font-weight: bold;">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Gesamt</td>
                <td>&nbsp;</td>
                <td class="currency"><?= $this->Number->currency($order->items_value_taxed, 'EUR'); ?></td>
            </tr>
        </table>

        <div class="actions" style="text-align: right;">
            <?= $this->Form->button(__d('shop','Update cart'), ['class' => 'btn btn-primary']); ?>&nbsp;&nbsp;
            <?= $this->Ui->link(__d('shop','Continue shopping'), ['controller' => 'Catalogue', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>&nbsp;&nbsp;
            <?= $this->Ui->link(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>
        </div>

        <div class="ui divider"></div>
        <?= $this->element('Shop.Cart/customer_info'); ?>

        <?= $this->Form->end(); ?>

    <?php else: ?>
        <div class="alert alert-warning">
            <h2><?= h(__d('shop', 'No items in cart')); ?></h2>
        </div>
        <hr />
        <div class="actions text-right" style="">
                <?= $this->Ui->link(__d('shop','Browse shop'),
                    ['controller' => 'Catalogue', 'action' => 'index'],
                    ['class' => 'btn btn-primary']); ?>
        </div>
    <?php endif; ?>
    <?php debug($order); ?>
    <?php debug($this->request->session()->read('Shop')); ?>
</div>