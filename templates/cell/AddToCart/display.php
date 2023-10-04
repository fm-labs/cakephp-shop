<?php
//@todo Move outside shop plugin
define("CAPTAINADVANCED", "CAPTAIN ADVANCED");
define("CAPTAINADVANCED_GROUP_ID", \Cake\Core\Configure::read('Ontalents.Shop.captainAdvancedGroupId'));
?>
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
        <div class="add-to-cart-submit" style="margin-top: 0.5em;">
            <?php if (!$auth): ?>
                <div class="add-to-cart add-to-cart-noauth">
                    <p class="m-0 p-0">
                    <?php
                    $url = ['_name' => 'user:login', 'goto' => urlencode($this->Html->Url->build($product->url))];
                    echo $this->Html->link(__d('shop','Login to purchase this product'), $url,
                        ['class' => 'btn btn-outline-primary']);
                    ?>
                    </p>
                </div>
            <?php elseif ((strtoupper((string)$product->custom1) === CAPTAINADVANCED
                && (string)$this->getRequest()->getSession()->read('Auth.group_id') !== CAPTAINADVANCED_GROUP_ID)
                || ($product->shop_category && strtoupper((string)$product->shop_category->custom1) === CAPTAINADVANCED
                && (string)$this->getRequest()->getSession()->read('Auth.group_id') !== CAPTAINADVANCED_GROUP_ID)): ?>
                <div class="alert alert-info">
                    <p class="m-0 y-0">
                        Dieses Produkt steht nur zertifizierten Benutzern zur Verfügung.
                    </p>
                </div>
            <?php else: ?>
                <?= $this->Form->create($form, $formOptions); ?>
                <?= $this->Form->controls($formInputs, $formInputsOptions); ?>
                <?= $this->Form->button(__d('shop', 'Add to cart'),
                    ['class' => 'btn btn-primary btn-addtocart']); ?>
                <?= $this->Form->end(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>