<?php if (!$auth) return; ?>
<?php if (!$product->isBuyable()): ?>
    <?= __d('shop','Not available') ?>
    <?php return; ?>
<?php endif; ?>
<?php if ($product->type == "parent"): ?>
    <?= $this->Html->link(__d('shop','View product'), $product->url, ['class' => 'btn btn-primary']); ?>
    <?php return; ?>
<?php endif; ?>
<div class="add-to-cart-button">
    <div class="add-to-cart-form">
        <?= $this->Form->create($form, $formOptions); ?>
        <?= $this->Form->controls($formInputs, $formInputsOptions); ?>
        <div class="add-to-cart-submit" style="margin-top: 0.5em;">
            <?= $this->Form->button(__d('shop', 'Add to cart'),
                ['class' => 'btn btn-primary btn-addtocart']); ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>