<?php $this->assign('heading', __d('shop','Edit billing address')); ?>
<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','My Account'), ['controller' => 'Customer', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Saved addresses'), ['controller' => 'CustomerAddresses', 'action' => 'index', 'ref' => 'breadcrumb']);
?>
<div class="shop customer addresses form container">

    <h1 class="heading"><?= __d('shop','Edit address'); ?></h1>

    <?= $this->cell('Shop.AddressForm', [$address]); ?>
</div>