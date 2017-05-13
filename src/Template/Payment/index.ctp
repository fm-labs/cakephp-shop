<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','My Orders'), ['controller' => 'Orders', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','View order details and status'), ['controller' => 'Orders', 'action' => 'view', $order->uuid, 'ref' => 'breadcrumb']);
?>

<div class="payment index container">
    <h1><?= __('Order payment'); ?></h1>



    <?php
    /**
     * Show provider specific 'pay' element
     */
    $element = 'Shop.Payment/' . $order->payment_type . '/pay';
    echo ($this->elementExists($element)) ? $this->element($element) : "";
    ?>



    <hr />
    <?= $this->Html->link(
        __('Back to order'),
        ['controller' => 'Orders', 'action' => 'view', $order->uuid],
        ['class' => 'btn btn-default']
    ); ?>


</div>