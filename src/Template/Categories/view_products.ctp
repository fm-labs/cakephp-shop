<?php //$this->extend('extend_view'); ?>
<?php $this->loadHelper('Media.Media'); ?>
<div class="shop categories view products">
    <div class="shop category">
        <h1>
            <?= h($shopCategory->name) ?>
        </h1>

        <div class="ui stackable grid">
            <div class="row">
                <div class="col-md-9">
                    <?php foreach($shopCategory->products as $shopProduct): ?>
                    <article class="product" itemscope itemtype="http://schema.org/Product">
                        <div class="image">
                            <?php if ($shopProduct->featured_image_file): ?>
                                <?= $this->Media->thumbnail($shopProduct->featured_image_file->filepath, ['width' => 200, 'height' => 200], [
                                    'url' => $shopProduct->url,
                                    'itemprop' => 'image'
                                ]); ?>
                            <?php endif; ?>
                        </div>
                        <h3 itemprop="title"><?= $this->Html->link($shopProduct->title, $shopProduct->url); ?></h3>
                        <div class="desc text html" itemprop="description">
                            <?= $this->Content->userHtml($shopProduct->teaser_html); ?>
                        </div>

                        <?php if ($shopProduct->sku): ?>
                        <div class="sku">
                            Art. <span itemprop="sku"><?= $shopProduct->sku; ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if ($shopProduct->price > 0): ?>
                        <div class="price" itemprop="price">
                            <?= $this->Number->currency($shopProduct->price, 'EUR'); ?>
                        </div>
                        <?php elseif ($shopProduct->price == -1): ?>
                        <div class="price" itemprop="price">
                            Preis auf Anfrage
                        </div>
                        <?php endif; ?>
                    </article>
                    <?php endforeach; ?>
                    <div class="clearfix"></div>

                    <div class="ui hidden divider"></div>
                    <div class="link goback">
                        <?= $this->Html->link(__d('theme_lederleitner', 'Go back'), 'javascript:history.go(-1)', ['class' => 'ui primary button']); ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <?php if ($shopCategory->desc_html): ?>
                    <div class="category desc text html">
                        <?= $this->Content->userHtml($shopCategory->desc_html); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>