<?php $this->Html->meta('robots', 'noindex,nofollow', ['block' => true]); ?>
<?php $this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index']); ?>
<?php $this->Breadcrumbs->add(__d('shop','Cart'), ['action' => 'index']); ?>
<?php $this->assign('title', __d('shop', 'Cart')); ?>
<div class="index shop cart empty">

    <div class="row">
        <div class="col-md-12">
            <h1><?= __d('shop', 'Cart'); ?></h1>
            <p style="font-size: 1.3em;"><?= __d('shop', 'You don\'t have any products in your cart'); ?></p>
            <div class="actions" style="">
                <?= $this->Html->link(__d('shop','Browse shop'),
                    ['controller' => 'Shop', 'action' => 'index'],
                    ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    </div>

    <?php debug($order); ?>
    <?php debug($this->request->session()->read('Shop')); ?>
</div>