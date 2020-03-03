<?php $this->assign('title', $shopProduct->title); ?>
<div class="shop products view default">
    <article class="shop product" itemscope itemtype="http://schema.org/Product">
        <h1 class="title" itemprop="name"><?= h($shopProduct->title); ?></h1>
        <?php if ($shopProduct->shop_category): ?>
        <h3><?= __d('shop','Category'); ?>:
            <?= $this->Html->link($shopProduct->shop_category->name, $shopProduct->shop_category->getUrl()); ?></h3>
        <?php endif; ?>
        <div class="ui grid">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="text desc-short" itemprop="description">
                        <?= $this->Content->userHtml($shopProduct->teaser_html); ?>
                    </div>
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
                    <div class="text">
                        <?= __d('shop','Art-Nr.:'); ?> <span itemprop="sku"><?= $shopProduct->sku; ?></span>
                    </div>
                    <div class="price" itemprop="price">
                        <strong>Stück <?= $this->Number->currency($shopProduct->price, 'EUR'); ?></strong>
                    </div>
                    <div class="text desc-long" itemprop="description">
                        <?= $this->Content->userHtml($shopProduct->desc_html); ?>
                    </div>

                    <?php if ($shopProduct->is_buyable): ?>
                    <div class="ui divider"></div>
                    <div class="ui form add-to-cart">
                        <?= $this->Form->create(null, [
                            'url' => ['controller' => 'Cart', 'action' => 'add', $shopProduct->id ]
                        ]); ?>

                        <?php if ($shopProduct->type == 'parent'): ?>
                            <?= $this->Form->input('refid', [
                                'type' => 'select',
                                'options' => $childProducts,
                                'label' => false
                            ]); ?>
                        <?php else: ?>
                            <?= $this->Form->hidden('refid', ['value' => $shopProduct->id]); ?>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-sm-4">
                                <?= $this->Form->text('amount', ['type' => 'number', 'default' => 1, 'step' => 1, 'min' => 1, 'max' => '1000']); ?>
                            </div>
                            <div class="col-sm-8">
                                <span style="vertical-align: bottom; display: block; margin-top: 1em;"><?= __d('shop','Unit'); ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" style="margin-top: 0.5em;">
                                <?= $this->Form->submit(__d('shop', 'Add to cart'),
                                    ['class' => 'btn btn-primary btn-block addtocart button']); ?>
                            </div>
                        </div>
                        <?= $this->Form->end(); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($shopProduct->media_images): ?>
            <?php foreach ($shopProduct->media_images as $mediaImage): ?>
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
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="ui hidden divider"></div>
                    <div class="link goback">
                        <?= $this->Html->link(__d('shop', 'Go back'), 'javascript:history.go(-1)', ['class' => 'ui primary button']); ?>
                    </div>
                </div>
                <div class="col-md-6">
                   &nbsp;
                </div>
            </div>

        </div>
    </article>

    <?php debug($shopProduct); ?>
    <?php //debug($shopProduct->getDescShort()); ?>
    <?php //debug($shopProduct->getDescLong()); ?>
</div>