<?php $this->Breadcrumbs->add(__('Shop Customer Discounts'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($shopCustomerDiscount->id); ?>
<?php $this->Toolbar->addLink(
    __('Edit {0}', __('Shop Customer Discount')),
    ['action' => 'edit', $shopCustomerDiscount->id],
    ['data-icon' => 'edit']
) ?>
<?php $this->Toolbar->addLink(
    __('Delete {0}', __('Shop Customer Discount')),
    ['action' => 'delete', $shopCustomerDiscount->id],
    ['data-icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', $shopCustomerDiscount->id)]) ?>

<?php $this->Toolbar->addLink(
    __('List {0}', __('Shop Customer Discounts')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __('New {0}', __('Shop Customer Discount')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->startGroup(__('More')); ?>
<?php $this->Toolbar->addLink(
    __('List {0}', __('Shop Customers')),
    ['controller' => 'ShopCustomers', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __('New {0}', __('Shop Customer')),
    ['controller' => 'ShopCustomers', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __('List {0}', __('Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __('New {0}', __('Shop Product')),
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
            <td><?= __('Shop Customer') ?></td>
            <td><?= $shopCustomerDiscount->has('shop_customer') ? $this->Html->link($shopCustomerDiscount->shop_customer->display_name, ['controller' => 'ShopCustomers', 'action' => 'view', $shopCustomerDiscount->shop_customer->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __('Shop Product') ?></td>
            <td><?= $shopCustomerDiscount->has('shop_product') ? $this->Html->link($shopCustomerDiscount->shop_product->title, ['controller' => 'ShopProducts', 'action' => 'view', $shopCustomerDiscount->shop_product->id]) : '' ?></td>
        </tr>
        <tr>
            <td><?= __('Type') ?></td>
            <td><?= h($shopCustomerDiscount->type) ?></td>
        </tr>
        <tr>
            <td><?= __('Valuetype') ?></td>
            <td><?= h($shopCustomerDiscount->valuetype) ?></td>
        </tr>


        <tr>
            <td><?= __('Id') ?></td>
            <td><?= $this->Number->format($shopCustomerDiscount->id) ?></td>
        </tr>
        <tr>
            <td><?= __('Value') ?></td>
            <td><?= $this->Number->format($shopCustomerDiscount->value) ?></td>
        </tr>


        <tr class="date">
            <td><?= __('Publish Start') ?></td>
            <td><?= h($shopCustomerDiscount->publish_start) ?></td>
        </tr>
        <tr class="date">
            <td><?= __('Publish End') ?></td>
            <td><?= h($shopCustomerDiscount->publish_end) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __('Is Published') ?></td>
            <td><?= $shopCustomerDiscount->is_published ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
-->



