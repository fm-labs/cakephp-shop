<?php $this->extend('/Admin/Base/index'); ?>
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
                        <?= nl2br(h($shopOrder->billing_address_formatted)) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default panel-primary">
                    <div class="panel-heading">
                        <?= __d('shop','Shipping Address'); ?>
                    </div>
                    <div class="panel-body">
                        <?= nl2br(h($shopOrder->shipping_address_formatted)) ?>
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
                        <th><?= __d('shop','Id') ?></th>
                        <th><?= __d('shop','Title') ?></th>
                        <th><?= __d('shop','Amount') ?></th>
                        <th><?= __d('shop','Unit') ?></th>
                        <th><?= __d('shop','Tax Rate') ?></th>
                        <th><?= __d('shop','Value Total') ?></th>
                        <th class="actions"><?= __d('shop','Actions') ?></th>
                    </tr>
                    <?php foreach ($shopOrder->shop_order_items as $shopOrderItems): ?>
                        <tr>
                            <td><?= h($shopOrderItems->id) ?></td>
                            <td><?= h($shopOrderItems->title) ?></td>
                            <td><?= h($shopOrderItems->amount) ?></td>
                            <td><?= h($shopOrderItems->unit) ?></td>
                            <td><?= h($shopOrderItems->tax_rate) ?></td>
                            <td><?= h($shopOrderItems->value_total) ?></td>

                            <td class="actions">
                                <?= $this->Html->link(__d('shop','View'), ['controller' => 'ShopOrderItems', 'action' => 'view', $shopOrderItems->id]) ?>
                                <?= $this->Html->link(__d('shop','Edit'), ['controller' => 'ShopOrderItems', 'action' => 'edit', $shopOrderItems->id]) ?>
                                <?= $this->Form->postLink(__d('shop','Delete'), ['controller' => 'ShopOrderItems', 'action' => 'delete', $shopOrderItems->id], ['confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopOrderItems->id)]) ?>
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
    <?= $this->Tabs->add('Data Table'); ?>
    <?= $this->cell('Backend.EntityView', [ $shopOrder ], [
        'debug' => true,
        'model' => 'Shop.ShopOrders',
        'fields' => [
        ],
        'exclude' => []
    ]); ?>

    <!-- @TODO Related data -->
    <?= $this->Tabs->render(); ?>


</div>

