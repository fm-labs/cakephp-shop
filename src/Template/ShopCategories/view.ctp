<?php $this->extend('extend_view'); ?>
<div class="shop categories view default">
    <div class="shop category">
        <h1 class="title"><?= h($shopCategory->name); ?></h1>
        <div class="ui grid">
            <div class="row">
                <div class="seven wide column">
                    <div class="teaser text html">
                        <?= $shopCategory->teaser_html; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="seven wide column">
                    <div class="image">
                        <?php if ($shopCategory->featured_image_file): ?>
                            <?php
                            $img = $this->Html->image($shopCategory->featured_image_file->url);
                            //echo $this->Html->link($img, ['action' => 'view', $shopCategory->id], ['escape' => false]);
                            echo $img;
                            ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="eight wide column">
                    <div class="desc text html">
                        <?= $this->Content->userHtml($shopCategory->desc_html); ?>
                    </div>

                    <div>
                        <ul class="tags">
                            <?php foreach ($shopCategory->tags as $tag): ?>
                                <li class="tag <?= $tag->class; ?>" title="<?= h($shopCategory->name . " ist geeignet für " . $tag->name); ?>">&nbsp;</li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php debug($shopCategory); ?>
</div>