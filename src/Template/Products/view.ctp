<?php $this->assign('title', $shopProduct->title); ?>
<div class="shop products view default container">
    <article class="shop product" itemscope itemtype="http://schema.org/Product">
        <div class="ui grid">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <?php echo $this->element('Shop.Products/product_image'); ?>
                </div>
                <div class="col-md-6 col-xs-12">

                    <h1 class="title" itemprop="name"><?= h($shopProduct->title); ?></h1>

                    <?php echo $this->element('Shop.Products/product_info_teaser'); ?>
                    <?php echo $this->element('Shop.Products/product_info_price'); ?>

                    <?php //echo $this->element('Shop.Products/product_info'); ?>
                    <?php echo $this->element('Shop.Products/product_info_availability'); ?>
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
</div>