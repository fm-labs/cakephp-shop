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
                    echo $this->Form->input('shop_order_id', ['options' => $shopOrders]);
                echo $this->Form->input('refscope');
                echo $this->Form->input('refid');
                echo $this->Form->input('title');
                echo $this->Form->input('amount');
                echo $this->Form->input('unit');
                echo $this->Form->input('item_value_net');
                echo $this->Form->input('tax_rate');
                echo $this->Form->input('value_net');
                echo $this->Form->input('value_tax');
                echo $this->Form->input('value_total');
                echo $this->Form->input('options');
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__d('shop','Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>