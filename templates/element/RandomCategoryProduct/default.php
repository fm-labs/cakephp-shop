<div class="shop random product">
    <h1 class="title"><?= h($product->title); ?></h1>
    <div class="teaser text html">
        <?= $product->teaser_html; ?>
    </div>
    <div class="">
        <?= $this->Html->link(__d('shop','See more'), $product->url); ?>
    </div>
</div>