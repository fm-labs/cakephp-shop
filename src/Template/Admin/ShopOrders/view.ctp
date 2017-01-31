<?php $this->extend('Backend./Base/index'); ?>
<?php $this->loadHelper('Bootstrap.Tabs'); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:admin:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop Orders'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Order #{0}', $shopOrder->nr_formatted)); ?>
<?= $this->Toolbar->addLink(
    __d('shop','Edit {0}', __d('shop','Shop Order')),
    ['action' => 'edit', $shopOrder->id],
    ['data-icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop','Delete {0}', __d('shop','Shop Order')),
    ['action' => 'delete', $shopOrder->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopOrder->id)]) ?>

<div class="shopOrders view">
    <h1>
        <?= __d('shop','Order {0}', $shopOrder->nr_formatted); ?>
    </h1>

    <?= $this->Tabs->start(); ?>


    <?= $this->Tabs->add(__d('shop','Order details')); ?>

    <div class="order" style="max-width: 1000px;">

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default panel-primary">
                    <div class="panel-heading">
                        <?= __d('shop','Billing Address'); ?>
                    </div>
                    <div class="panel-body">
                        <?= $this->element('Shop.address', ['address' => $shopOrder->billing_address]) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default panel-primary">
                    <div class="panel-heading">
                        <?= __d('shop','Shipping Address'); ?>
                    </div>
                    <div class="panel-body">
                        <?php if ($shopOrder->shipping_address): ?>
                        <?= $this->element('Shop.address', ['address' => $shopOrder->shipping_address]) ?>
                        <?php else: ?>
                            <?= $this->element('Shop.address', ['address' => $shopOrder->billing_address]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default panel-primary">
            <div class="panel-heading">
                <?= __d('shop','Related {0}', __d('shop','ShopOrderItems')) ?>
            </div>
            <?php if (!empty($shopOrder->shop_order_items)): ?>
                <table class="table">
                    <tr>
                        <th><?= __d('shop','ArtikelNr') ?></th>
                        <th><?= __d('shop','Amount') ?></th>
                        <th><?= __d('shop','Unit') ?></th>
                        <th><?= __d('shop','Title') ?></th>
                        <th><?= __d('shop','Tax Rate') ?></th>
                        <th><?= __d('shop','Value Total') ?></th>
                        <th class="actions"><?= __d('shop','Actions') ?></th>
                    </tr>
                    <?php foreach ($shopOrder->shop_order_items as $shopOrderItem): ?>
                        <tr>
                            <td><?= h($shopOrderItem->getProduct()->getSku()) ?></td>
                            <td><?= h($shopOrderItem->amount) ?></td>
                            <td><?= h($shopOrderItem->unit) ?></td>
                            <td><?= h($shopOrderItem->getProduct()->getTitle()) ?></td>
                            <td><?= h($shopOrderItem->tax_rate) ?></td>
                            <td class="right"><?= h($shopOrderItem->value_total) ?></td>

                            <td class="actions">
                                <?= $this->Html->link(__d('shop','View'), ['controller' => 'ShopOrderItems', 'action' => 'view', $shopOrderItem->id]) ?>
                                <?= $this->Html->link(__d('shop','Edit'), ['controller' => 'ShopOrderItems', 'action' => 'edit', $shopOrderItem->id]) ?>
                                <?= $this->Form->postLink(__d('shop','Delete'), ['controller' => 'ShopOrderItems', 'action' => 'delete', $shopOrderItem->id], ['confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopOrderItem->id)]) ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>

        <div class="panel panel-default panel-primary">
            <div class="panel-heading">
                Payment
            </div>
            <?= $this->cell('Backend.EntityView', [ $shopOrder ], [
                'title' => false,
                'model' => 'Shop.ShopOrders',
                'fields' => [
                    'payment_type',
                    'payment_info_1',
                    'payment_info_2',
                    'payment_info_3'
                ],
                'exclude' => '*'
            ])->render('table'); ?>
        </div>

        <div class="panel panel-default panel-primary">
            <div class="panel-heading">
                Additional Order Info
            </div>
            <?= $this->cell('Backend.EntityView', [ $shopOrder ], [
                'title' => false,
                'model' => 'Shop.ShopOrders',
                'fields' => [
                    'uuid',
                    'cartid',
                    'sessionid',
                    'shop_customer',
                    'status'
                ],
                'exclude' => '*'
            ])->render('table'); ?>
        </div>
    </div>



    <!-- Data Table -->
    <?= $this->Tabs->add('Order Entity'); ?>
    <?= $this->cell('Backend.EntityView', [ $shopOrder ], [
        'debug' => true,
        'model' => 'Shop.ShopOrders',
        'fields' => [
        ],
        'exclude' => []
    ]); ?>




    <!-- Data Table -->
    <?= $this->Tabs->add('Order Items Table', ['id' => 'order-items']); ?>
    <?= $this->cell('Backend.DataTable', [[
        'data' => $shopOrder->shop_order_items,
        'debug' => true,
        'model' => 'Shop.ShopOrderItems',
        'fields' => [
            'sku',
            'title',
            'amount',
            'unit',
            'value_net',
            'value_tax'
        ],
        'exclude' => [],

        'rowActions' => [
            [__d('shop','Edit'), ['action' => 'item_edit', ':id'],
                ['class' => 'edit']],
            [__d('shop','Delete'), ['action' => 'item_delete', ':id'],
                ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]); ?>

    <!-- @TODO Related data -->
    <?= $this->Tabs->render(); ?>


</div>

