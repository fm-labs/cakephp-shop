<?php $this->loadHelper('Backend.Ui'); ?>
<?php if (empty($order->shop_order_items)): ?>
    <h2><?= __d('shop','No items in cart'); ?></h2>
    <div class="ui divider"></div>
    <div class="ui primary submit actions button" style="text-align: right;">
        <?= $this->Html->link(__d('shop','Continue shopping'), ['controller' => 'Catalogue', 'action' => 'index'], ['class' => '']); ?>
    </div>

    <?php return; ?>
<?php endif; ?>
<table class="table table-striped">
    <thead>
    <tr>
        <th><?= __d('shop','Amount'); ?></th>
        <th><?= __d('shop','Product'); ?></th>
        <th><?= __d('shop','Item Price'); ?></th>
        <th><?= __d('shop','Price'); ?></th>
    </tr>
    </thead>
    <?php foreach ($order->shop_order_items as $item): ?>
        <tr>
            <td>
                <?= h($item->amount) ?> <?= $item->unit; ?>
            </td>
            <td><?= h($item->title); ?></td>
            <td><?= $this->Number->currency($item->item_value_taxed, 'EUR'); ?></td>
            <td><?= $this->Number->currency($item->value_total, 'EUR'); ?></td>
        </tr>
    <?php endforeach; ?>
    <tr style="font-weight: bold;">
        <td>&nbsp;</td>
        <td>Gesamt</td>
        <td>&nbsp;</td>
        <td><?= $this->Number->currency($order->items_value_taxed, 'EUR'); ?></td>
    </tr>
</table>