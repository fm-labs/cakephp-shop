<?php //$this->extend('extend_view'); ?>
<?php $this->loadHelper('Media.Media'); ?>
<div class="shop categories view 2col subgrid">
    <div class="ui grid">
        <div class="row">
            <div class="col-md-6">
                <article class="featured" itemscope itemtype="http://schema.org/Product">
                    <div class="image">
                        <?php if ($shopCategory->featured_image_file): ?>
                            <?= $this->Html->image($shopCategory->featured_image_file->url); ?>
                        <?php endif; ?>
                    </div>

                    <div class="body" itemprop="description">
                        <?= $this->Content->userHtml($shopCategory->desc_html); ?>
                    </div>

                    <div class="ui hidden divider"></div>
                    <div class="link goback">
                        <?= $this->Html->link(__d('theme_lederleitner', 'Go back'), 'javascript:history.go(-1)', ['class' => 'ui primary button']); ?>
                    </div>
                </article>
            </div>
            <div class="col-md-6">
                <?php foreach ($shopCategory->published_subcategories as $subCategory): ?>
                    <article class="subcategory" itemscope itemtype="http://schema.org/Product">

                        <div class="image">
                            <?php if ($subCategory->preview_image_file): ?>
                                <?= $this->Media->thumbnail($subCategory->preview_image_file->filepath, ['width' => 266, 'height' => 150], [
                                    'height' => 150,
                                    'url' => $subCategory->url,
                                    'itemprop' => 'image'
                                ]); ?>
                            <?php endif; ?>
                        </div>
                        <div class="title">
                            <h2 itemprop="name"><?= $this->Html->link($subCategory->name, $subCategory->url); ?></h2>
                        </div>
                        <div class="clearfix"></div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php debug($shopCategory); ?>
</div>
