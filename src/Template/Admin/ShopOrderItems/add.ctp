<?php $this->Breadcrumbs->add(__d('shop','Shop Order Items'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','New {0}', __d('shop','Shop Order Item'))); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Order Items')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
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
<div class="form">
    <h2 class="ui header">
        <?= __d('shop','Add {0}', __d('shop','Shop Order Item')) ?>
    </h2>
    <?= $this->Form->create($shopOrderItem); ?>
    <div class="users ui basic segment">
        <div class="ui form">
        <?php
                    echo $this->Form->control('shop_order_id', ['options' => $shopOrders]);
                echo $this->Form->control('refscope');
                echo $this->Form->control('refid');
                echo $this->Form->control('title');
                echo $this->Form->control('amount');
                echo $this->Form->control('unit');
                echo $this->Form->control('item_value_net');
                echo $this->Form->control('tax_rate');
                echo $this->Form->control('value_net');
                echo $this->Form->control('value_tax');
                echo $this->Form->control('value_total');
                echo $this->Form->control('options');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>