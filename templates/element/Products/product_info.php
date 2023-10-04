<?php
$isAdvancedProduct = ((strtoupper((string)$shopProduct->custom1) === CAPTAINADVANCED)
    || ($shopProduct->shop_category && strtoupper((string)$shopProduct->shop_category->custom1) === CAPTAINADVANCED));

$isAdvancedUser = (string)$this->getRequest()->getSession()->read('Auth.group_id') !== CAPTAINADVANCED_GROUP_ID;
?>

<div class="product-info-item product-info text">

    <dl class="dl-horizontal">
        <dt style="text-align: left;"><?= __d('shop','Art-Nr.'); ?></dt>
        <dd><span itemprop="sku"><?= $shopProduct->sku; ?></span></dd>

        <?php if ($shopProduct->shop_category): ?>
        <dt style="text-align: left;"><?= __d('shop','Category'); ?></dt>
        <dd><?= $this->Html->link($shopProduct->shop_category->name, $shopProduct->shop_category->getUrl()); ?></dd>
        <?php endif; ?>

        <dt style="text-align: left;"><?= __d('shop','Certification required'); ?></dt>
        <dd><span><?= $isAdvancedProduct ? __('Yes') : __('No'); ?></span></dd>
    </dl>

</div>
