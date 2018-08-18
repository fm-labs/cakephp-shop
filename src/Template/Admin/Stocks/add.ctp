<?php $this->Breadcrumbs->add(__d('shop','Stocks'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','New {0}', __d('shop','Stock'))); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Stocks')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Stock Transfers')),
    ['controller' => 'ShopStockTransfers', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Stock Transfer')),
    ['controller' => 'ShopStockTransfers', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Stock Values')),
    ['controller' => 'ShopStockValues', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Stock Value')),
    ['controller' => 'ShopStockValues', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop','Add {0}', __d('shop','Stock')) ?>
    </h2>
    <?= $this->Form->create($stock, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                echo $this->Form->input('title');
                echo $this->Form->input('is_default');
        ?>
        </div>

    <?= $this->Form->button(__d('shop','Submit')) ?>
    <?= $this->Form->end() ?>

</div>