<?php
$this->Html->meta('robots', 'noindex,nofollow', ['block' => true]);
//$this->assign('title', $this->fetch('heading'));
?>
<?php $this->Breadcrumbs->add(__d('shop', 'Cart'), ['_name' => 'shop:cart']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Checkout'), ['_name' => 'shop:checkout']); ?>
<?php foreach ((array) $this->get('steps') as $stepId => $step): ?>

<?php
    $class = '';
    if ($stepId == $this->fetch('step_active')) {
        $class .= 'active';
    }
    if ($step['is_complete'] == true) {
        $class .= ' completed';
    }
    $this->Breadcrumbs->add(
        $step['title'],
        ['controller' => 'Checkout', 'action' => $stepId, 'ref' => 'breadcrumb'],
        ['class' => $class]
    ); ?>
<?php endforeach; ?>
<div class="shop checkout index container">

    <?= $this->fetch('content'); ?>

    <?php if (\Cake\Core\Configure::read('debug')): ?>
        <hr />
        CartID: <br />
        <?= h($this->get('cartId')); ?>
        <?php debug($this->request->session()->read('Shop')); ?>
    <?php endif; ?>
</div>