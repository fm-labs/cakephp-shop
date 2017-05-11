<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','My Account'), ['controller' => 'Customer', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Saved addresses'), ['controller' => 'CustomerAddresses', 'action' => 'index', 'ref' => 'breadcrumb']);
?>
<div class="shop customer-addresses index container">

    <h1 class="heading"><?= __('Saved addresses'); ?></h1>
    <p>
        <?= __('This addresses can be used during the checkout process'); ?>
    </p>
    <?= $this->Html->link(__('Add address'), ['action' => 'add'], ['class' => 'btn btn-lg btn-primary']); ?>
    <hr />

    <?php if (count($addresses) < 1): ?>
        <div class="alert alert-info">
            <strong><?= __('No saved addresses'); ?></strong>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($addresses as $address): ?>
                <div class="col-md-4">
                    <?php echo $this->element('Shop.address', compact('address')); ?>
                    <?= $this->Html->link(__('Edit address'), ['action' => 'edit', $address->id], ['class' => 'btn btn-default']); ?>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>