<?php $this->Breadcrumbs->add(__d('shop','My Account'), ['controller' => 'Customer', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Latest Orders'), ['action' => 'index']); ?>
<?php $this->loadHelper('Banana.Status'); ?>
<div class="shopOrders index container">

    <h1><?= __('Your latest orders'); ?></h1>

    <?php if (count($shopOrders) < 1): ?>
        <div class="alert alert-warning">
            <h4><?= __('No orders found'); ?></h4>
            <?= $this->Html->link(__('Browse shop'), ['_name' => 'shop:index'], ['class' => 'btn btn-primary']); ?>
        </div>
    <?php else: ?>
        <table class="table">
            <tr>
                <th><?= $this->Paginator->sort('nr'); ?></th>
                <th><?= $this->Paginator->sort('submitted'); ?></th>
                <th><?= $this->Paginator->sort('order_value_total'); ?></th>
                <th><?= $this->Paginator->sort('status'); ?></th>
                <th class="actions">Actions</th>
            </tr>
            <?php foreach($shopOrders as $order): ?>
            <tr>
                <td><?= $this->Html->link($order->nr_formatted, ['action' => 'view', $order->uuid]); ?></td>
                <td><?= h($order->submitted); ?></td>
                <td><?= $this->Number->currency($order->order_value_total, $order->currency); ?></td>
                <td><?= $this->Status->label($order->status); ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View details'), ['action' => 'view', $order->uuid]); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
