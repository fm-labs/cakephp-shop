<?php
$this->Html->meta('robots', 'noindex,nofollow', ['block' => true]);
?>
<?php $this->Breadcrumbs->add(__d('shop', 'Cart'), ['_name' => 'shop:cart']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Checkout'), ['controller' => 'Checkout', 'action' => 'index', $order->cartid, 'ref' => 'breadcrumb']); ?>
<?php foreach ((array) $this->get('steps') as $stepId => $step): ?>
    <?php
    $class = '';
    if ($stepId == $this->fetch('step_active')) {
        $class .= 'active';

        /*
        $this->Breadcrumbs->add(
            $step['title'],
            ['controller' => 'Checkout', 'action' => $stepId, 'ref' => 'breadcrumb'],
            ['class' => $class]
        );
        */
    }
    elseif ($step['is_complete'] == true) {
        $class .= ' completed';

    }

    $this->Breadcrumbs->add(
        $step['title'],
        ['controller' => 'Checkout', 'action' => $stepId, $order->cartid, 'ref' => 'breadcrumb'],
        ['class' => $class]
    );


    ?>
<?php endforeach; ?>
<div class="shop checkout index container">

    <!--
    -->
    <h1 class="heading"><?= $this->fetch('heading'); ?></h1>
    <?= $this->fetch('content'); ?>

    <?php if (\Cake\Core\Configure::read('debug')): ?>
        <hr />
        CartID: <br />
        <?= h($this->get('cartId')); ?>
        <?php debug($this->request->getSession()->read('Shop')); ?>
    <?php endif; ?>
</div>