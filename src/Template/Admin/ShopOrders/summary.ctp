<?php // $this->extend('Backend./Base/index'); ?>
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

    <div class="order">

        <div class="row-header">
            <h1>
                <?= __d('shop','Order No. {0}', $shopOrder->nr_formatted); ?>
            </h1>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?= $this->cell('Backend.EntityView', [ $shopOrder ], [
                    'title' => false,
                    'model' => 'Shop.ShopOrders',
                    'whitelist' => true,
                    'fields' => [
                        '_status' => ['formatter' => function($val, $row, $args, $view) {
                            return $this->Status->label($val);
                        }],
                        'shop_customer_id' => ['formatter' => function($val, $row) {
                            return ($row->shop_customer) ? $this->Html->link($row->shop_customer->displayName, ['controller' => 'ShopCustomers', 'action' => 'view', $row->shop_customer->id]) : null;
                        }],
                        'submitted' => [],
                        'nr_formatted' => ['formatter' => function($val, $row) {
                            return $this->Html->link($val, ['action' => 'view', $row->id]);
                        }],
                        'ordergroup' => [],
                        'title' => ['formatter' => function() {}],
                        'items_value_taxed' => [],
                        'order_value_total' => [],
                        'shipping_type' => [],
                        'payment_type' => [],
                        'payment_info_1' => [],
                        'payment_info_2' => [],
                        'payment_info_3' => [],
                        'customer_phone' => [],
                        'customer_mail' => [],
                        'is_temporary' => [],
                        'is_storno' => [],
                        'is_deleted' => [],
                    ],
                ])->render(); ?>
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

    </div>

</div>

