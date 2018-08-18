<?php $this->Breadcrumbs->add(__d('shop','Shop Customers'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($shopCustomer->display_name); ?>
<?php $this->Toolbar->addLink(
    __d('shop','Edit {0}', __d('shop','Shop Customer')),
    ['action' => 'edit', $shopCustomer->id],
    ['data-icon' => 'edit']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','Delete {0}', __d('shop','Shop Customer')),
    ['action' => 'delete', $shopCustomer->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', $shopCustomer->id)]) ?>

<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Customers')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Customer')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->startGroup(__d('shop','More')); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Addresses')),
    ['controller' => 'ShopAddresses', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Address')),
    ['controller' => 'ShopAddresses', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Orders')),
    ['controller' => 'ShopOrders', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Order')),
    ['controller' => 'ShopOrders', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<?php $this->assign('heading', h($shopCustomer->display_name)); ?>
<?php $this->loadHelper('Bootstrap.Tabs'); ?>
<div class="shopCustomers view">

    <?php $this->Tabs->create(); ?>
    <?php $this->Tabs->add(__d('shop','Customer')); ?>

    <div class="actions text-right">
        <?= $this->Html->link(__d('shop','Edit'), ['action' => 'edit', $shopCustomer->id], ['class' => 'btn btn-primary btn-edit']); ?>
    </div>

    <?= $this->cell('Backend.EntityView', [ $shopCustomer ], [
        'title' => false,
        'model' => 'Shop.ShopCustomers',
        'fields' => [
            'title' => [
                'formatter' => function($val, $entity) {
                    return $this->Html->link($val, ['action' => 'edit', $entity->id], ['class' => 'link-frame']);
                }
            ],
        ],
        'exclude' => []
    ]); ?>

    <?= $this->Tabs->add(__d('shop','Orders'), ['url' => ['controller' => 'ShopOrders', 'action' => 'index', 'shop_customer_id' => $shopCustomer->id]]); ?>
    <?= $this->Tabs->add(__d('shop','Addresses'), ['url' => ['controller' => 'ShopCustomerAddresses', 'action' => 'index', 'shop_customer_id' => $shopCustomer->id]]); ?>

    <?= $this->Tabs->render(); ?>
</div>
