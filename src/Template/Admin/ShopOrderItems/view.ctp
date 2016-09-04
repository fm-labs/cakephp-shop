<?php $this->Html->addCrumb(__d('shop','Shop Order Items'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($shopOrderItem->title); ?>
<?= $this->Toolbar->addLink(
    __d('shop','Edit {0}', __d('shop','Shop Order Item')),
    ['action' => 'edit', $shopOrderItem->id],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','Delete {0}', __d('shop','Shop Order Item')),
    ['action' => 'delete', $shopOrderItem->id],
    ['icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopOrderItem->id)]) ?>

<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Order Items')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Order Item')),
    ['action' => 'add'],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__d('shop','More')); ?>
<?= $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Orders')),
    ['controller' => 'ShopOrders', 'action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Order')),
    ['controller' => 'ShopOrders', 'action' => 'add'],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->endGroup(); ?>
<div class="shopOrderItems view">
    <h2 class="ui header">
        <?= h($shopOrderItem->title) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __d('shop','Label'); ?></th>
            <th><?= __d('shop','Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __d('shop','Shop Order') ?></td>
            <td><?= $shopOrderItem->has('shop_order') ? $this->Html->link($shopOrderItem->shop_order->title, ['controller' => 'ShopOrders', 'action' => 'view', $shopOrderItem->shop_order->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Refscope') ?></td>
            <td><?= h($shopOrderItem->refscope) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Title') ?></td>
            <td><?= h($shopOrderItem->title) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Unit') ?></td>
            <td><?= h($shopOrderItem->unit) ?></td>
        </tr>


        <tr>
            <td><?= __d('shop','Id') ?></td>
            <td><?= $this->Number->format($shopOrderItem->id) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Refid') ?></td>
            <td><?= $this->Number->format($shopOrderItem->refid) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Amount') ?></td>
            <td><?= $this->Number->format($shopOrderItem->amount) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Item Value Net') ?></td>
            <td><?= $this->Number->format($shopOrderItem->item_value_net) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Tax Rate') ?></td>
            <td><?= $this->Number->format($shopOrderItem->tax_rate) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Value Net') ?></td>
            <td><?= $this->Number->format($shopOrderItem->value_net) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Value Tax') ?></td>
            <td><?= $this->Number->format($shopOrderItem->value_tax) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop','Value Total') ?></td>
            <td><?= $this->Number->format($shopOrderItem->value_total) ?></td>
        </tr>


        <tr class="date">
            <td><?= __d('shop','Created') ?></td>
            <td><?= h($shopOrderItem->created) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop','Modified') ?></td>
            <td><?= h($shopOrderItem->modified) ?></td>
        </tr>

        <tr class="text">
            <td><?= __d('shop','Options') ?></td>
            <td><?= $this->Text->autoParagraph(h($shopOrderItem->options)); ?></td>
        </tr>
    </table>
</div>
