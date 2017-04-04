<?php //$this->extend('extend_view'); ?>
<div class="shop categories view default container">
    <article class="shop category" itemscope itemtype="http://schema.org/Product">
        <h1 class="title"><span itemprop="name"><?= h($shopCategory->name); ?></span></h1>
        <div class="ui grid">
            <div class="row">
                <div class="col-md-5">
                    <div class="teaser text html" itemprop="description">
                        <?= $this->Content->userHtml($shopCategory->teaser_html); ?>
                    </div>

                </div>
                <div class="col-md-7">

                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="image">
                        <?php if ($shopCategory->featured_image_file): ?>
                            <?php
                            $img = $this->Html->image($shopCategory->featured_image_file->url, [
                                'alt' => $shopCategory->name,
                                'title' => $shopCategory->name,
                                'itemprop' => 'image'
                            ]);
                            //echo $this->Html->link($img, ['action' => 'view', $shopCategory->id], ['escape' => false]);
                            echo $img;
                            ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="desc text html" itemprop="description">
                        <?= $this->Content->userHtml($shopCategory->desc_html); ?>
                    </div>
                </div>
            </div>

            <?php if ($shopCategory->has('media_images')): ?>
            <?php foreach($shopCategory->media_images as $mediaImage): ?>
            <div class="row">
                <div class="col-md-5">
                    <div class="image">
                        <?php
                        $img = $this->Html->image($mediaImage->url);
                        echo $img;
                        ?>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="desc text html">
                        <?= $this->Content->userHtml($mediaImage->desc); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-5">
                    &nbsp;
                </div>
                <div class="col-md-7">
                    <ul class="tags">
                        <?php foreach ($shopCategory->tags as $tag): ?>
                            <li class="tag <?= $tag->class; ?>" title="<?= h($shopCategory->name . " ist geeignet für " . $tag->name); ?>">&nbsp;</li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="ui divider"></div>

                    <div class="related">
                        <?= $this->Content->userHtml($shopCategory->custom_text1); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5">
                    <div class="ui hidden divider"></div>
                    <div class="link goback">
                        <?= $this->Html->link(__d('shop', 'Go back'), 'javascript:history.go(-1)', ['class' => 'ui primary button']); ?>
                    </div>
                </div>
                <div class="col-md-7">
                    &nbsp;
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </article>

</div>