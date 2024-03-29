<?php $this->Html->meta('robots', 'noindex,nofollow', ['block' => true]); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Cart'), ['action' => 'index']); ?>
<?php $this->assign('title', __d('shop', 'Cart')); ?>
<div class="index shop cart empty container">

    <div class="row">
        <div class="col-md-12">
            <h1><?= __d('shop', 'Cart'); ?></h1>
            <p style="font-size: 1.3em;"><?= __d('shop', 'You don\'t have any products in your cart'); ?></p>
            <div class="actions" style="">
                <?= $this->Html->link(__d('shop','View products'),
                    ['controller' => 'Shop', 'action' => 'index'],
                    ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    </div>
</div>