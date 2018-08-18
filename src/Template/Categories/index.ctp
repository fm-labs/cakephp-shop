<div class="shop categories index container">
    <h1>Shop Categories</h1>
    <?php foreach($shopCategories as $shopCategory): ?>
    <?php //echo $this->element('Shop.debug_shop_category', ['shopCategory' => $shopCategory]); ?>
        <section class="shop category">
            <h1><?= $this->Html->link($shopCategory->name, $shopCategory->view_url); ?></h1>
            <div class="desc">
                <?= $this->Content->userHtml($shopCategory->desc_html); ?>
            </div>
        </section>
    <?php endforeach; ?>
</div>