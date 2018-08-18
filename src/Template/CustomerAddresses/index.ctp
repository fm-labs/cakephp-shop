<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','My Account'), ['controller' => 'Customer', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Saved addresses'), ['controller' => 'CustomerAddresses', 'action' => 'index', 'ref' => 'breadcrumb']);
?>
<div class="shop customer-addresses index container">

    <h1 class="heading"><?= __d('shop','Saved addresses'); ?></h1>
    <p>
        <?= __d('shop','This addresses can be used during the checkout process'); ?>
    </p>
    <?= $this->Html->link(__d('shop','Add address'), ['action' => 'add'], ['class' => 'btn btn-primary']); ?>
    <hr />

    <?php if (count($addresses) < 1): ?>
        <div class="alert alert-info">
            <strong><?= __d('shop','No saved addresses'); ?></strong>
        </div>
    <?php else: ?>
        <div class="row">
            <?php $i = 0 ; ?>
            <?php foreach ($addresses as $address): ?>
                <div class="col-md-4">
                    <?php echo $this->element('Shop.address', compact('address')); ?>
                    <?= $this->Html->link(__d('shop','Edit address'), ['action' => 'edit', $address->id], ['class' => 'btn btn-default']); ?>
                </div>
                <?php if (++$i % 3 == 0) echo '</div><div class="row">'; ?>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>