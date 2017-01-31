<?php //$this->extend('extend_view'); ?>
<?php $this->loadHelper('Media.Media'); ?>
<?php $i = 0; ?>
<div class="shop categories view threecol productsgrid">

    <div class="alert alert-info">products_grid</div>
    <div class="ui three column grid">
        <div class="row">
        <?php foreach($shopCategory->products as $shopProduct): ?>
            <div class="column">
                <div class="product">
                    <div class="image">
                        <?php if ($shopProduct->preview_image): ?>
                            <?php
                            $img = $this->Media->thumbnail($shopProduct->preview_image->url);
                            echo $this->Html->link($img, $shopProduct->url, ['escape' => false]);
                            ?>
                        <?php endif; ?>
                    </div>
                    <h1><?= $this->Html->link($shopProduct->title, $shopProduct->url); ?></h1>
                </div>
            </div>
            <?= (++$i % 3 == 0) ? '</div><div class="row">' : ''; ?>
        <?php endforeach; ?>
        </div>
    </div>

    <?php debug($shopCategory->shop_products); ?>

</div>