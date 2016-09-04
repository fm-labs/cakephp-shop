<?php
/*
try {
    $url = \Cake\Routing\Router::url(['plugin' => 'Shop', 'controller' => 'ShopProducts', 'action' => 'index', 'category_id' => $categoryId]);
    echo $this->requestAction($url);
} catch (\Exception $ex) {
    echo "ProductsListCell: " . $ex->getMessage();
}
*/
$this->loadHelper('Number');
$this->loadHelper('Paginator');
$this->loadHelper('Media.Media');
?>
<?php $i = 0; ?>
<div class="shop products index grid">
    <div class="ui grid">
        <div class="row">
            <?php foreach($shopProducts as $shopProduct): ?>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="product">
                        <div class="image">
                            <?php if ($shopProduct->preview_image): ?>
                                <?php
                                $img = $this->Media->thumbnail($shopProduct->preview_image->filepath, ['width' => 250, 'height' => 250]);
                                echo $this->Html->link($img, $shopProduct->url, ['escape' => false]);
                                ?>
                            <?php endif; ?>
                        </div>
                        <div class="body">
                            <h1 class="title"><?= $this->Html->link($shopProduct->title, $shopProduct->url); ?></h1>
                            <?php if ($options['show_teaser']): ?>
                            <div class="desc text html">
                                <?= $shopProduct->teaser_html; ?>
                            </div>
                            <?php endif; ?>
                            <?php if ($options['show_price']): ?>
                            <div class="price">
                                <?= $this->Number->currency($shopProduct->price, 'EUR'); ?>
                            </div>
                            <?php endif; ?>

                            <?php if ($options['add_to_cart'] && $shopProduct->is_buyable && !in_array($shopProduct->type, ['parent'])): ?>
                                <div class="ui form add-to-cart">
                                    <?= $this->Form->create(null, [
                                        'url' => ['controller' => 'Cart', 'action' => 'add', $shopProduct->id ]
                                    ]); ?>
                                    <?= $this->Form->hidden('amount', ['value' => 1]); ?>

                                    <?php if (isset($childProducts)): ?>
                                        <?= $this->Form->input('refid', [
                                            'type' => 'select',
                                            'options' => $childProducts,
                                            'label' => false
                                        ]); ?>
                                    <?php else: ?>
                                        <?= $this->Form->hidden('refid', ['value' => $shopProduct->id]); ?>
                                    <?php endif; ?>
                                    <?= $this->Form->submit(__d('shop', 'Add to cart'),
                                        ['class' => 'btn btn-primary addtocart button']); ?>
                                    <?= $this->Form->end(); ?>
                                </div>
                                <?php elseif ($options['add_to_cart']): ?>
                                <div>
                                    <?= $this->Html->link(__d('shop', 'Produkt ansehen'), $shopProduct->url,
                                        ['class' => 'btn btn-primary addtocart button']); ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
                <?= (++$i % 3 == 0) ? '</div><div class="row">' : ''; ?>
            <?php endforeach; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="ui divider"></div>

    <?= ''//$this->element('Shop.Pagination/default'); ?>
    <?php debug($shopProducts); ?>
</div>
