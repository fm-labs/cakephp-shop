<?php $this->extend('Backend./Base/index'); ?>
<?php $this->loadHelper('Bootstrap.Tabs'); ?>
<?php $this->loadHelper('Banana.Status'); ?>
<?php $this->loadHelper('Number'); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:admin:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop Orders'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Order #{0}', $shopOrder->nr_formatted)); ?>
<?php $this->Toolbar->addLink(
    __d('shop','Edit {0}', __d('shop','Shop Order')),
    ['action' => 'edit', $shopOrder->id],
    ['data-icon' => 'edit']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','Delete {0}', __d('shop','Shop Order')),
    ['action' => 'delete', $shopOrder->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopOrder->id)]) ?>
<?php $this->assign('title',__d('shop','Order {0}', $shopOrder->nr_formatted)); ?>
<div class="shopOrders view">

    <?= $this->Tabs->start(); ?>
    <?= $this->Tabs->add(__d('shop','Order details')); ?>

    <div class="order">

        <div class="row-header">
            <h1>
                <?= __('Order No. {0}', $shopOrder->nr_formatted); ?>
                <?= $this->Status->label($shopOrder->status); ?>
            </h1>
        </div>

        <div class="row">
            <div class="col-md-10">
                <dl class="dl-horizontal">
                    <dt><i class="fa fa-calendar"></i> Purchased on</dt>
                    <dd><?= $this->Time->nice($shopOrder->submitted); ?></dd>
                </dl>
            </div>
            <div class="col-md-2">
                <!--
                <div class="actions action-vertical">
                    <?= $this->Html->link(__('Confirm order'), '#', ['class' => 'btn btn-primary btn-sm']); ?>
                    <?= $this->Html->link(__('Hold order'), '#', ['class' => 'btn btn-default btn-sm']); ?>
                    <?= $this->Html->link(__('Cancel order'), '#', ['class' => 'btn btn-danger btn-sm']); ?>
                </div>
                -->
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h3>Billing Address</h3>
                <?= $this->element('Shop.address', ['address' => $shopOrder->billing_address]) ?>
            </div>
            <div class="col-md-6">
                <h3>Shipping Address</h3>
                <?= $this->element('Shop.address', ['address' => $shopOrder->shipping_address]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>Order Items</h3>
                <div class="order-items">
                    <?php $pos = 0; // index counter work-around ?>
                    <?= $this->cell('Backend.DataTable', [[
                        'paginate' => false,
                        'model' => 'Shop.ShopOrderItems',
                        'data' => $shopOrder->shop_order_items,
                        'class' => 'table table-condensed table-striped table-hover',
                        'fields' => [
                            'id' => [
                                'title' => __('Pos'),
                                'formatter' => function($val, $row) use (&$pos) {
                                    return $this->Html->link(++$pos, ['action' => 'view', $row->id]);
                                }
                            ],
                            'product_sku' => [
                                'formatter' => function($val, $row) {
                                    return ($val) ?: $row->getProduct()->getSku();
                                }
                            ],
                            'product_title' => [
                                'formatter' => function($val, $row) {
                                    return ($val) ?: $row->getProduct()->getTitle();
                                }
                            ],
                            'amount' => ['formatter' => function($val, $row) {
                                return sprintf("%d %s", $val, $row->unit);
                            }],
                            'delivered' => ['formatter' => function($val, $row) {
                                $delivered = 0; // @TODO Implemente me
                                return $delivered;
                            }],
                            'pending' => ['formatter' => function($val, $row) {
                                $pending = $row->amount; // @TODO Implemente me
                                return $pending;
                            }],
                            'product_onstock' => ['title' => 'Stock', 'formatter' => function($val, $row) {
                                $onStock = 0; // @TODO Implemente me
                                return $onStock;
                            }],

                            /*
                            'value_tax' => ['formatter' => function($val, $row) use ($shopOrder) {
                                return $this->Number->currency($val, $shopOrder->currency);
                            }],
                            'value_net' => ['formatter' => function($val, $row) use ($shopOrder) {
                                return $this->Number->currency($val, $shopOrder->currency);
                            }],
                            'value' => ['title' => __('Total'), 'formatter' => function($val, $row) use ($shopOrder) {
                                $val = ($val) ?: $row->value_net + $row->value_tax;
                                return $this->Number->currency($val, $shopOrder->currency);

                            }],
                            */
                            'status' => ['formatter' => function($val, $row) {
                                $pending = $row->amount;  // @TODO Implemente me
                                $status = ($pending == 0) ? 'DELIVERED' : 'PENDING';
                                return $status;
                            }],
                        ],
                        'rowActions' => false
                    ]]);
                    ?>
                </div>
            </div>
        </div>


    </div>


    <!-- Tab:OrderItems -->
    <?php $this->Tabs->add('Order Items', ['id' => 'order-items', 'url' => ['controller' => 'ShopOrderItems', 'action' => 'index', 'order_id' => $shopOrder->id]]); ?>

    <!-- Tab:Billing -->
    <?php // $this->Tabs->add(__('Billing Address'), ['url' => ['controller' => 'ShopOrderAddresses', 'action' => 'index', 'shop_order_id' => $shopOrder->id]]); ?>


    <!-- Tab:Shipping -->
    <?php // $this->Tabs->add(__('Shipping Address'), ['url' => ['controller' => 'ShopOrderAddresses', 'action' => 'index', 'shop_order_id' => $shopOrder->id]]); ?>



    <?php $this->Tabs->add('Billing'); ?>
    <div class="row">
        <div class="col-md-12">
            <h2><?= __('Billing Address'); ?></h2>
            <?= $this->cell('Backend.EntityView', [ $shopOrder->billing_address ], [
                'title' => false,
                'model' => 'Shop.ShopOrderAddresses',
                'fields' => [
                    'first_name',
                    'last_name',
                    'street',
                    'street2',
                    'zipcode',
                    'city',
                    'country_id'
                ],
                'exclude' => '*'
            ])->render('table'); ?>
            <hr />
            <h2><?= __('Invoices') ?></h2>
        </div>
    </div>

    <!-- Tab:Shipping -->
    <?= $this->Tabs->add('Shipping', ['id' => 'order-shipping']); ?>
    <div class="row">
        <div class="col-md-12">
            <h2><?= __('Shipping Address'); ?></h2>
            <?= $this->cell('Backend.EntityView', [ ($shopOrder->shipping_address) ?: $shopOrder->billing_address ], [
                'title' => false,
                'model' => 'Shop.ShopOrderAddresses',
                'fields' => [
                    'first_name',
                    'last_name',
                    'street',
                    'street2',
                    'zipcode',
                    'city',
                    'country_id',
                ],
                'exclude' => '*'
            ])->render('table'); ?>
        </div>
    </div>
    <!-- Tab:Payment -->
    <?= $this->Tabs->add('Payment', ['id' => 'order-payment']); ?>
    <div class="row">
        <div class="col-md-12">
            <h2><?= __('Payment'); ?></h2>
            <?= $this->cell('Backend.EntityView', [ $shopOrder ], [
                'title' => false,
                'model' => 'Shop.ShopOrders',
                'fields' => [
                    'payment_type',
                    'payment_info_1',
                    'payment_info_2',
                    'payment_info_3',
                    'payment_status' => ['formatter' => function($val) {
                        return $this->Status->label($val);
                    }],
                ],
                'exclude' => '*'
            ])->render('table'); ?>
        </div>
    </div>

    <!-- Tab:History -->
    <?= $this->Tabs->add('History', ['id' => 'order-history']); ?>


    <!-- Entity View -->
    <?= $this->Tabs->add('Admin'); ?>
    <?= $this->cell('Backend.EntityView', [ $shopOrder ], [
        'debug' => true,
        'model' => 'Shop.ShopOrders',
        'fields' => [
        ],
        'exclude' => []
    ]); ?>

    <?= $this->Tabs->add('Debug', ['debugOnly' => true]); ?>
    <?php debug($shopOrder); ?>
    <!-- @TODO Related data -->
    <?= $this->Tabs->render(); ?>


</div>

