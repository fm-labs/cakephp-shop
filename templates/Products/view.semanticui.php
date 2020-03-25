<?php $this->assign('title', $shopProduct->title); ?>
<div class="shop products view default compact container">
    <div class="shop product">
        <h1 class="title"><?= h($shopProduct->title); ?></h1>
        <div class="ui grid">
            <div class="row">
                <div class="seven wide column">
                    <div class="text desc-short">
                        <?= $shopProduct->teaser_html; ?>
                    </div>
                    <div class="image">
                        <?php if ($shopProduct->featured_image_file): ?>
                            <?php
                            $img = $this->Html->image($shopProduct->featured_image_file->url);
                            //echo $this->Html->link($img, ['action' => 'view', $shopProduct->id], ['escape' => false]);
                            echo $img;
                            ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="seven wide column">
                    <div class="text desc-long">
                        <?= $this->Content->userHtml($shopProduct->desc_html); ?>
                    </div>
                </div>
            </div>

            <?php foreach ($shopProduct->media_images as $mediaImage): ?>
            <div class="row">
                <div class="seven wide column">
                    <?php
                    $img = $this->Html->image($mediaImage->url, [
                        'title' => strip_tags($mediaImage->desc),
                        'alt' => $mediaImage->basename
                    ]);
                    echo $img;
                    ?>
                </div>
                <div class="seven wide column">
                    <?= $this->Content->userHtml($mediaImage->desc); ?>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
    </div>

    <?php debug($shopProduct); ?>
    <?php //debug($shopProduct->getDescShort()); ?>
    <?php //debug($shopProduct->getDescLong()); ?>
</div>