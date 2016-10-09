<?php $this->Html->addCrumb(__d('shop', 'Shop'), ['_name' => 'shop:admin:index']); ?>
<?php $this->Html->addCrumb(__d('shop', 'Shop Categories')); ?>
<?php $this->Toolbar->addLink(__d('shop', 'New {0}', __d('shop', 'Shop Category')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['data-icon' => 'plus', 'class' => 'link-frame-modal']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Repair'),
    ['action' => 'repair'],
    ['data-icon' => 'wrench', 'confirm' => __d('shop', 'Do you really want to repair the category tree?')]
) ?>
<?php $this->extend('Content./Admin/Base/index_jstree_ajax'); ?>
<?php $this->loadHelper('Backend.Datepicker'); ?>
<?php $this->assign('title', __d('shop','Shop Categories')); ?>
<?php $this->assign('treeHeading', __d('shop','Shop Categories')); ?>
