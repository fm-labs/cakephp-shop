<?php $this->loadHelper('Bootstrap.Ui'); ?>
<?php $this->loadHelper('Media.Media'); ?>
<?php $this->Html->meta('robots', 'noindex,nofollow', ['block' => true]); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Cart'), ['action' => 'index']); ?>
<?php $this->assign('title', __d('shop', 'Cart')); ?>
<div class="shop cart index">

    <?= $this->Form->create(null, ['url' => ['action' => 'cart_update', $order->id]]); ?>
    <?= $this->Form->hidden('id', ['value' => $order->id]); ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th class="number" style="width: 100px;"><?= __d('shop','Amount'); ?></th>
            <th style="width: 20px;">&nbsp;</th>
            <th colspan="2"><?= __d('shop','Product'); ?></th>
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
            <td class="image" style="width: 50px;">
                <?php if ($item->getProduct()->featured_image_file): ?>
                <?php echo $this->Media->thumbnail($item->getProduct()->featured_image_file->filepath, ['width' => 45, 'height' => 45]); ?>
                <?php endif; ?>
            </td>
            <td class="title">
                <?= $this->Html->link($item->title, $item->getProduct()->url); ?>
                <br />
                <small>
                    <?= $this->Ui->link(
                        __d('shop','Remove from cart'),
                        ['action' => 'remove', $order->id, $item->id],
                        ['data-icon' => 'trash', 'confirm' => __d('shop', 'Are you sure?')]
                    ); ?>
                </small>
            </td>
            <td class="number currency"><?= $this->Number->currency($item->item_value_taxed, 'EUR'); ?></td>
            <td class="number currency"><?= $this->Number->currency($item->value_total, 'EUR'); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr style="font-weight: bold; font-size: 1.3em;">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="text-align: right;"><?= __d('shop', 'Order total'); ?></td>
            <td class="currency"><?= $this->Number->currency($order->items_value_taxed, 'EUR'); ?></td>
        </tr>
    </table>

    <div class="actions" style="text-align: right;">
        <?= $this->Form->button(__d('shop','Update cart'), ['class' => 'btn btn-primary']); ?>&nbsp;&nbsp;
        <?= $this->Ui->link(__d('shop','Continue shopping'), ['controller' => 'Shop', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>&nbsp;&nbsp;
        <?= $this->Ui->link(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>
    </div>

    <div class="ui divider"></div>
    <?= $this->element('Shop.Cart/customer_info'); ?>

    <?= $this->Form->end(); ?>

    <?php debug($order); ?>
    <?php debug($this->request->session()->read('Shop')); ?>
</div>