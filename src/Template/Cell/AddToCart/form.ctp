<?php if (!$product->isBuyable()): ?>
    <?= __('This item is currently not available in our online store') ?>
    <?php return; ?>
<?php endif; ?>
<?php if ($product->type == "parent" && empty($productVersions) && \Cake\Core\Configure::read('debug')): ?>
    <div class="alert alert-warning">
        Product Type is set to parent, but no children found
    </div>
<?php endif; ?>
<div class="add-to-cart">
    <div class="add-to-cart-form">
        <?= $this->Form->create($form, $formOptions); ?>
        <?= $this->Form->inputs($formInputs, $formInputsOptions); ?>
        <div class="add-to-cart-submit" style="margin-top: 0.5em;">
            <?= $this->Form->button(__d('shop', 'Add to cart'),
                ['class' => 'btn btn-primary btn-addtocart']); ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>