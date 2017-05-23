<?php $this->Breadcrumbs->add(__('Shop Customer Discounts'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__('New {0}', __('Shop Customer Discount'))); ?>
<?php $this->Toolbar->addLink(
    __('List {0}', __('Shop Customer Discounts')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
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
<div class="form">
    <h2 class="ui header">
        <?= __('Add {0}', __('Shop Customer Discount')) ?>
    </h2>
    <?= $this->Form->create($shopCustomerDiscount, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                    echo $this->Form->input('shop_customer_id', ['options' => $shopCustomers]);
                    echo $this->Form->input('shop_product_id', ['options' => $shopProducts]);
                echo $this->Form->input('type');
                echo $this->Form->input('valuetype');
                echo $this->Form->input('value');
                echo $this->Form->input('is_published');
                //echo $this->Form->input('publish_start');
                //echo $this->Form->input('publish_end');
        ?>
        </div>

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

</div>