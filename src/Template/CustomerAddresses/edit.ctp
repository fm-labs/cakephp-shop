<?php $this->assign('heading', __d('shop','Edit billing address')); ?>
<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','My Account'), ['controller' => 'Customer', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Addressbook'), ['controller' => 'CustomerAddresses', 'action' => 'index', 'ref' => 'breadcrumb']);
?>
<div class="shop customer addresses form container">

    <h1 class="heading"><?= __('Edit address'); ?></h1>

    <div class="form">
        <?= $this->Form->create($address, ['horizontal' => false, 'novalidate' => true]); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $this->Form->input('first_name', ['label' => __d('shop','First Name')]); ?>
            </div>
            <div class="col-md-6">
                <?= $this->Form->input('last_name', ['label' => __d('shop','Last Name')]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $this->Form->input('street', ['label' => __d('shop','Street')]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?= $this->Form->input('zipcode', ['label' => __d('shop','Zipcode')]); ?>
            </div>
            <div class="col-md-9">
                <?= $this->Form->input('city', ['label' => __d('shop', 'City')]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $this->Form->input('country_id', ['label' => __d('shop','Country'), 'options' => $this->get('countries')]); ?>
            </div>
        </div>
        <div class="actions" style="margin-top: 1em;">
            <?= $this->Form->submit(__d('shop','Save'), ['class' => 'btn btn-lg btn-primary']); ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>