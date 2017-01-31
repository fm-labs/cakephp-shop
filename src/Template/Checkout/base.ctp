<?php
$this->Html->meta('robots', 'noindex,nofollow', ['block' => true]);
$this->assign('title', $this->fetch('heading'));
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
        ['controller' => 'Checkout', 'action' => 'step', 'step' => $stepId, 'ref' => 'breadcrumb'],
        ['class' => $class]
    ); ?>
<?php endforeach; ?>
<div class="shop checkout index">
    <!--
    <h1><?= __d('shop', 'Your Order'); ?>: <?= $this->fetch('heading'); ?></h1>
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

        <div class="col-md-12">
            <!--
            <h1><?= $this->fetch('heading'); ?></h1>
            -->
            <!--
            <ul class="list-group">

                <?php foreach ((array) $this->get('steps') as $stepId => $step): ?>
                    <?php
                    $element = 'Shop.Checkout/' . \Cake\Utility\Inflector::camelize($stepId) . '/step';
                    $class = 'list-group-item';
                    if ($stepId == $this->fetch('step_active')) {
                        break;
                    }
                    if ($step['is_complete'] == true) {
                        $class .= ' completed';
                    }

                    if ($this->elementExists($element)):
                        echo $this->element($element, ['step' => $step, 'class' => $class]);
                    else:
                        ?>
                        <li class="<?= $class ?>">
                            <h4 class="list-group-item-heading">
                                <span class="icon" style="width: 25px; display: inline-block; text-align: right; padding-right: 5px;">
                                <i class="fa fa-<?= $step['icon']; ?>"></i>
                                </span>
                                <?= $this->Html->link($step['title'], $step['url']); ?>
                            </h4>
                            <p class="list-group-item-text">
                            </p>
                        </li>
                    <?php endif; ?>
                    <?php
                    if ($stepId == $this->fetch('step_active')) {
                        break;
                    }
                    ?>
                <?php endforeach; ?>
            </ul>
            -->

            <?= $this->fetch('content'); ?>


            <?php if (\Cake\Core\Configure::read('debug')): ?>
                <hr />
                CartID: <br />
                <?= h($this->get('cartId')); ?>
                <?php debug($this->request->session()->read('Shop')); ?>
            <?php endif; ?>

            <?php
            ?>
        </div>
    </div>

</div>