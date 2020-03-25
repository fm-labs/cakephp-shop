<?php //$this->extend('extend_view'); ?>
<?php $i = 0; ?>
<div class="shop categories view threecol productsgrid container">

    <?= $cell = $this->cell('Shop.Category::products', [
            'category_id' => $shopCategory->id,
            'options' => [
                'add_to_cart' => true,
                'show_price' => true,
                'show_teaser' => true,
            ]
        ])->render('products_grid');
    ?>
</div>