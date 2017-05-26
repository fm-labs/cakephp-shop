<?php $this->Breadcrumbs->add(__d('shop', 'Shop Customer Discounts'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($shopCustomerDiscount->id); ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'Edit {0}', __d('shop', 'Shop Customer Discount')),
    ['action' => 'edit', $shopCustomerDiscount->id],
    ['data-icon' => 'edit']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'Delete {0}', __d('shop', 'Shop Customer Discount')),
    ['action' => 'delete', $shopCustomerDiscount->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopCustomerDiscount->id)]) ?>

<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Customer Discounts')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Customer Discount')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->startGroup(__d('shop', 'More')); ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Customers')),
    ['controller' => 'ShopCustomers', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Customer')),
    ['controller' => 'ShopCustomers', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="shopCustomerDiscounts view">
    <h2 class="ui header">
        <?= h($shopCustomerDiscount->id) ?>
    </h2>

    <?php
    echo $this->cell('Backend.EntityView', [ $shopCustomerDiscount ], [
        'title' => $shopCustomerDiscount->title,
        'model' => 'Shop.ShopCustomerDiscounts',
    ]);
    ?>

<!--
    <table class="ui attached celled striped table">


        <tr>
            <td><?= __d('shop', 'Shop Customer') ?></td>
            <td><?= $shopCustomerDiscount->has('shop_customer') ? $this->Html->link($shopCustomerDiscount->shop_customer->display_name, ['controller' => 'ShopCustomers', 'action' => 'view', $shopCustomerDiscount->shop_customer->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Shop Product') ?></td>
            <td><?= $shopCustomerDiscount->has('shop_product') ? $this->Html->link($shopCustomerDiscount->shop_product->title, ['controller' => 'ShopProducts', 'action' => 'view', $shopCustomerDiscount->shop_product->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Type') ?></td>
            <td><?= h($shopCustomerDiscount->type) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Valuetype') ?></td>
            <td><?= h($shopCustomerDiscount->valuetype) ?></td>
        </tr>


        <tr>
            <td><?= __d('shop', 'Id') ?></td>
            <td><?= $this->Number->format($shopCustomerDiscount->id) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Value') ?></td>
            <td><?= $this->Number->format($shopCustomerDiscount->value) ?></td>
        </tr>


        <tr class="date">
            <td><?= __d('shop', 'Publish Start') ?></td>
            <td><?= h($shopCustomerDiscount->publish_start) ?></td>
        </tr>
        <tr class="date">
            <td><?= __d('shop', 'Publish End') ?></td>
            <td><?= h($shopCustomerDiscount->publish_end) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __d('shop', 'Is Published') ?></td>
            <td><?= $shopCustomerDiscount->is_published ? __d('shop', 'Yes') : __d('shop', 'No'); ?></td>
        </tr>
    </table>
</div>
-->



