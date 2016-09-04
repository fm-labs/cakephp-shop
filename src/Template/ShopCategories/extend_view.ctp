<?php $this->Html->addCrumb('Alle Natursteine', ['_name' => 'shop:index']); ?>
<?php $this->Html->addCrumb($shopCategory->name); ?>
<?php $this->assign('title', $shopCategory->name); ?>
<?= $this->fetch('content'); ?>
