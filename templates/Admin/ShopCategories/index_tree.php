<?php $this->Breadcrumbs->add(__d('shop', 'Shop'), ['_name' => 'shop:admin:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Shop Categories'), ['action' => 'index']); ?>

<?php $this->Toolbar->addLink(__d('shop', 'New {0}', __d('shop', 'Shop Category')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['data-icon' => 'plus', 'class' => 'link-frame-modal']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'Repair'),
    ['action' => 'repair'],
    ['data-icon' => 'wrench', 'confirm' => __d('shop', 'Do you really want to repair the category tree?')]
) ?>
<?php $this->extend('Admin./Base/index_jstree_ajax'); ?>
<?php $this->assign('title', __d('shop','Shop Categories')); ?>
<?php //$this->assign('treeHeading', __d('shop','Shop Categories')); ?>
