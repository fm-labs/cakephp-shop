<?php $this->loadHelper('Number'); ?>
<?php $i = 0; ?>
<div class="shop products index grid container">

    <div class="ui three column grid">
        <div class="row">
            <?php foreach($shopProducts as $shopProduct): ?>
                <div class="column">
                    <div class="product">
                        <div class="image">
                            <?php if ($shopProduct->preview_image): ?>
                                <?php
                                $img = $this->Html->image($shopProduct->preview_image->url);
                                echo $this->Html->link($img, $shopProduct->url, ['escape' => false]);
                                ?>
                            <?php endif; ?>
                        </div>
                        <div class="body">
                            <h1 class="title"><?= $this->Html->link($shopProduct->title, $shopProduct->url); ?></h1>
                            <div class="desc text html" style="display: none">
                                <?= $this->Content->userHtml($shopProduct->teaser_html); ?>
                            </div>
                            <div class="price">
                                <?= $this->Number->currency($shopProduct->price, 'EUR'); ?>
                            </div>
                            <div class="ui actions buttons">
                                <?= $this->Html->link(__d('shop', 'Add to cart'),
                                    ['controller' => 'Cart', 'action' => 'add', 'ShopProducts', $shopProduct->id ],
                                    ['class' => 'ui button']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?= (++$i % 3 == 0) ? '</div><div class="row">' : ''; ?>
            <?php endforeach; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="ui divider"></div>

    <?= $this->element('Shop.Pagination/default'); ?>
    <?php debug($shopProducts); ?>
</div>