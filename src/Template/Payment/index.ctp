<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','My Orders'), ['controller' => 'Orders', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','View order details and status'), ['controller' => 'Orders', 'action' => 'view', $order->uuid, 'ref' => 'breadcrumb']);
?>

<div class="payment index container">
    <h1><?= __d('shop','Order payment'); ?></h1>


    <?php
    $element = 'Shop.Payment/' . $order->payment_type . '/pay';
    if ($this->elementExists($element)) {
        echo $this->element($element);
        //return;
    }
    ?>

    <hr />
    <?= $this->Html->link(
        __d('shop','Back to order'),
        ['controller' => 'Orders', 'action' => 'view', $order->uuid],
        ['class' => 'btn btn-default']
    ); ?>


</div>