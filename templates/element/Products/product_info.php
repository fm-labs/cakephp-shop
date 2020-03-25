<div class="product-info-item product-info text">

    <dl class="dl-horizontal">
        <dt style="text-align: left;"><?= __d('shop','Art-Nr.'); ?></dt>
        <dd><span itemprop="sku"><?= $shopProduct->sku; ?></span></dd>

        <?php if ($shopProduct->shop_category): ?>
        <dt style="text-align: left;"><?= __d('shop','Category'); ?></dt>
        <dd><?= $this->Html->link($shopProduct->shop_category->name, $shopProduct->shop_category->getUrl()); ?></dd>
        <?php endif; ?>
    </dl>

</div>
