<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('step_active', 'cart'); ?>
<?php $this->assign('heading', __d('shop','Cart')); ?>
<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Cart'), ['controller' => 'Checkout', 'action' => 'cart', 'ref' => 'breadcrumb']);
?>
<?php $this->start('active_step'); ?>
ACTIVE STEP!
<?php $this->end(); ?>
<div class="shop checkout step cart">
    <?php if ($order): ?>
        <?= $this->element('Shop.Checkout/cart'); ?>

        <div class="ui actions" style="text-align: right;">
            <?= $this->Html->link(__d('shop','Continue'), ['action' => 'next'], ['class' => 'btn btn-primary']); ?>
        </div>
    <?php else: ?>
        <div class="actions">
            <blockquote><?= __d('shop','You don\'t have selected any items yet'); ?></blockquote>
            <br />
            <?= $this->Html->link(__d('shop','Browse shop'), ['_name' => 'shop:index'], ['class' => 'btn btn-primary']); ?>
        </div>
    <?php endif; ?>
</div>
