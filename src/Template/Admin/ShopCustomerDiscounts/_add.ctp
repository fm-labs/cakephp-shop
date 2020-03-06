<?php $this->Breadcrumbs->add(__d('shop', 'Shop Customer Discounts'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'New {0}', __d('shop', 'Shop Customer Discount'))); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop', 'Add {0}', __d('shop', 'Shop Customer Discount')) ?>
    </h2>
    <?= $this->Form->create($shopCustomerDiscount, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                    echo $this->Form->control('shop_customer_id', ['options' => $shopCustomers]);
                    echo $this->Form->control('shop_product_id', ['options' => $shopProducts]);
                echo $this->Form->control('type');
                echo $this->Form->control('valuetype');
                echo $this->Form->control('value');
                echo $this->Form->control('is_published');
                //echo $this->Form->control('publish_start');
                //echo $this->Form->control('publish_end');
        ?>
        </div>

    <?= $this->Form->button(__d('shop', 'Submit')) ?>
    <?= $this->Form->end() ?>

</div>