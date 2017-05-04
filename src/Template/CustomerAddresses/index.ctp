<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','My Account'), ['controller' => 'Customer', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Addressbook'), ['controller' => 'CustomerAddresses', 'action' => 'index', 'ref' => 'breadcrumb']);
?>
<div class="shop customer-addresses index container">

    <h1 class="heading">
        My address book
    </h1>

    <?php foreach ($addresses as $address): ?>
        <?php echo $this->element('Shop.address', compact('address')); ?>
        <hr />
    <?php endforeach; ?>

    <?php debug($addresses); ?>
</div>