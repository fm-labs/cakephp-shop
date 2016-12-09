<?php
$this->assign('title', $this->fetch('heading'));
$this->Html->meta('robots', 'noindex,nofollow', ['block' => true]);
?>
<div class="shop checkout index">
    <h1><?= __d('shop', 'Your Order'); ?>: <?= $this->fetch('heading'); ?></h1>
    <!--
    <div class="cart panel panel-default">
        <div class="panel-heading">
            Ihre Bestellung: <?= $this->Number->currency($order->items_value_taxed, 'EUR') ?>
        </div>
        <div class="panel-body">
            <?= $this->Html->link(__d('shop','See cart'), ['action' => 'cart'], ['data-icon' => 'cart', 'class' => 'btn btn-default']); ?>
        </div>
    </div>

    <hr />
    -->

    <div class="row">

        <div class="col-md-8">
            <?= $this->fetch('content'); ?>

            <?php // debug($this->request->session()->read('Shop')); ?>
        </div>
        <div class="col-md-4">
            <ul class="list-group">

                <?php foreach ($steps as $method => $step): ?>
                    <?php
                    $element = 'Shop.Checkout/' . \Cake\Utility\Inflector::camelize($method) . '/step';
                    $class = 'list-group-item';
                    if ($method == $this->fetch('step_active')) {
                        $class .= ' active';
                    }
                    if ($step['complete'] == true) {
                        $class .= ' completed';
                    }

                    if ($this->elementExists($element)):
                        echo $this->element($element, ['step' => $step, 'method' => $method, 'class' => $class]);
                    else:
                    ?>
                        <li class="<?= $class ?>">
                            <h4 class="list-group-item-heading">
                                <span class="icon" style="width: 25px; display: inline-block; text-align: right; padding-right: 5px;">
                                <i class="fa fa-<?= $step['icon']; ?>"></i>
                                </span>
                                <?= $this->Html->link($step['title'], ['action' => $method, 'ref' => 'step']); ?>
                            </h4>
                            <p class="list-group-item-text">
                            </p>
                        </li>
                        <?php endif; ?>
                <?php endforeach; ?>
            </ul>


            <?php if (\Cake\Core\Configure::read('debug')): ?>
                <hr />
                CartID: <br />
                <?= h($cartId); ?>
            <?php endif; ?>

        </div>
    </div>

</div>