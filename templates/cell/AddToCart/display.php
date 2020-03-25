<?php if (!$product->isBuyable()): ?>
    <?= __d('shop','This item is currently not available in our online store') ?>
    <?php return; ?>
<?php endif; ?>
<?php if ($product->type == "parent" && $type == "button"): ?>
    <?= $this->Html->link(__d('shop','View product'), $product->url, ['class' => 'btn btn-primary']); ?>
    <?php return; ?>
<?php endif; ?>
<div class="add-to-cart">
    <div class="add-to-cart-form">
        <?= $this->Form->create($form, $formOptions); ?>
        <?= $this->Form->controls($formInputs, $formInputsOptions); ?>
        <div class="add-to-cart-submit" style="margin-top: 0.5em;">
            <?php if (!$auth): ?>
                <div class="add-to-cart add-to-cart-noauth">
                    <p>
                    <?php
                    $url = ['_name' => 'user:login', 'goto' => urlencode($this->Html->Url->build($product->url))];
                    echo $this->Html->link(__d('shop','Login to purchase this product'), $url, ['class' => 'btn btn-default']);
                    ?>
                    </p>
                </div>
            <?php else: ?>
                <?= $this->Form->button(__d('shop', 'Add to cart'),
                    ['class' => 'btn btn-primary btn-addtocart']); ?>
            <?php endif; ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>