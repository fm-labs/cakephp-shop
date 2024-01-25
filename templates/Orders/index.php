<?php $this->Breadcrumbs->add(__d('shop','My Account'), ['controller' => 'Customer', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Latest Orders'), ['action' => 'index']); ?>
<?php $this->loadHelper('Cupcake.Status'); ?>
<?php $this->loadHelper('Time', ['outputTimezone' => 'Europe/Vienna']); ?>
<?php $this->assign('title', __d('shop','Latest orders')); ?>
<div class="shopOrders index container">

    <h1><?= __d('shop','Your latest orders'); ?></h1>

    <?php if (count($shopOrders) < 1): ?>
        <div class="alert alert-warning">
            <h4><?= __d('shop','No orders found'); ?></h4>
            <?= $this->Html->link(__d('shop','Browse shop'), ['_name' => 'shop:index'], ['class' => 'btn btn-primary']); ?>
        </div>
    <?php else: ?>
        <table class="table">
            <tr>
                <th><?= $this->Paginator->sort('nr', __d('shop','Order Nr')); ?></th>
                <th><?= $this->Paginator->sort('submitted', __d('shop','Date')); ?></th>
                <th><?= $this->Paginator->sort('order_value_total', __d('shop','Order Total')); ?></th>
                <th><?= $this->Paginator->sort('status'); ?></th>
                <th class="actions"><?= __d('shop','Actions'); ?></th>
            </tr>
            <?php foreach($shopOrders as $order): ?>
            <tr>
                <td><?= $this->Html->link($order->nr_formatted, ['action' => 'view', $order->uuid]); ?></td>
                <td><?= $this->Time->i18nFormat($order->submitted); ?></td>
                <td><?= $this->Number->currency($order->order_value_total, $order->currency); ?></td>
                <td><?= $this->Status->label($order->status__status); ?></td>
                <td class="actions">
                    <?= $this->Html->link(__d('shop','View details'), ['action' => 'view', $order->uuid]); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
