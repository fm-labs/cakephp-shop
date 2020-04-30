<?php $this->extend('Admin./Base/index'); ?>
<?php $this->loadHelper('Bootstrap.Tabs'); ?>
<?php $this->loadHelper('Cupcake.Status'); ?>
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
    <?= $this->Tabs->add(__d('shop','Order calculation')); ?>

    <?= $this->element('Shop.Order/cost_calculator', compact('calculator')); ?>

    <?= $this->Tabs->render(); ?>

    <?php debug($calculator->toArray()); ?>

</div>

