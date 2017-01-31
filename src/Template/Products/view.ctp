<?php $this->assign('title', $shopProduct->title); ?>
<div class="shop products view default">
    <article class="shop product" itemscope itemtype="http://schema.org/Product">
        <div class="ui grid">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="image">
                        <?php if ($shopProduct->featured_image_file): ?>
                            <?php
                            $img = $this->Html->image($shopProduct->featured_image_file->url, ['itemprop' => 'image']);
                            //echo $this->Html->link($img, ['action' => 'view', $shopProduct->id], ['escape' => false]);
                            echo $img;
                            ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">

                    <h1 class="title" itemprop="name"><?= h($shopProduct->title); ?></h1>
                    <div class="text">
                        <strong><?= __d('theme_lederleitner','Art-Nr.:'); ?></strong>
                        <span itemprop="sku"><?= $shopProduct->sku; ?></span>
                    </div>

                    <?php if ($shopProduct->shop_category): ?>
                        <strong><?= __d('theme_lederleitner','Category'); ?>:</strong>
                        <?= $this->Html->link($shopProduct->shop_category->name, $shopProduct->shop_category->url); ?>
                    <?php endif; ?>
                    <div class="price" itemprop="price" style="text-align: right;">
                        <strong><?= __d('shop', 'per unit'); ?></strong>
                        <span style="font-size: 3em; font-weight: bold;">
                            <?= $this->Number->currency($shopProduct->price, 'EUR'); ?>
                        </span>
                    </div>


                    <hr />
                    <div class="text desc-short" itemprop="description">
                        <?= $this->Content->userHtml($shopProduct->teaser_html); ?>
                    </div>

                    <hr />
                    <div class="availability">
                        <strong><?= __d('shop', 'Availability'); ?></strong>
                        <?= __d('shop', 'Store only'); ?>
                    </div>

                    <hr />
                    <?php echo $this->cell('Shop.AddToCart::form', [['qty' => true]], compact('shopProduct')); ?>
                </div>
            </div>

            <!-- Product description -->
            <div class="description">
                <h2><?= __d('shop', 'Product description'); ?></h2>
                <div class="text desc-long" itemprop="description">
                    <?= $this->Content->userHtml($shopProduct->desc_html); ?>
                </div>
            </div>

            <!-- Media images -->
            <div class="media-images">
                <h2><?= __d('shop', 'Images'); ?></h2>
                <?php foreach ((array) $shopProduct->media_images as $mediaImage): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            $img = $this->Html->image($mediaImage->url, [
                                'title' => strip_tags($mediaImage->desc),
                                'alt' => $mediaImage->basename
                            ]);
                            echo $img;
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Content->userHtml($mediaImage->desc); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </article>

    <?php debug($shopProduct); ?>
</div>