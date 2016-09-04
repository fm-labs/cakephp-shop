<?php $this->Html->addCrumb(__d('shop', 'Shop'), ['_name' => 'shop:admin:index']); ?>
<?php $this->Html->addCrumb(__d('shop', 'Shop Categories')); ?>
<?php $this->Toolbar->addLink(__d('shop', 'New {0}', __d('shop', 'Shop Category')), ['action' => 'add'], ['icon' => 'plus']); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['icon' => 'plus', 'class' => 'link-frame-modal']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Repair'),
    ['action' => 'repair'],
    ['icon' => 'wrench', 'confirm' => __d('shop', 'Do you really want to repair the category tree?')]
) ?>
<?php $this->extend('Banana./Admin/Base/index_jstree_ajax'); ?>
<?php $this->assign('title', __('Shop Categories')); ?>
<?php $this->assign('treeHeading', __('Shop Categories')); ?>
