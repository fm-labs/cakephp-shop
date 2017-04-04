<?php //$this->extend('extend_view'); ?>
<?php $this->loadHelper('Media.Media'); ?>
<div class="shop categories view complex subgrid container">
    
    <?php $subCategories = $shopCategory->published_subcategories->toArray(); ?>
    <?php for ($i = 0; $i < count($subCategories); $i += 3): ?>
    <?php
    $category1 = (isset($subCategories[$i])) ? $subCategories[$i] : null;
    $category2 = (isset($subCategories[$i+1])) ? $subCategories[$i+1] : null;
    $category3 = (isset($subCategories[$i+2])) ? $subCategories[$i+2] : null;
    ?>
        <div class="subcontainer">
            <div class="col col1">
                <!-- Category 1 -->
                <?php if ($category1): ?>
                <article class="subcategory subcategory1" itemscope itemtype="http://schema.org/Product">
                    <div class="image">
                        <div class="inside">
                        <?php if ($category1->preview_image_file): ?>
                        <?= $this->Media->thumbnail($category1->preview_image_file->filepath, ['width' => 375, 'height' => 300], [
                                'url' => $category1->url,
                                'itemprop' => 'image'
                            ]); ?>
                        <?php endif; ?>
                        </div>
                    </div>
                    <div class="teaser">
                        <div class="inside">
                        <h3 class="title"><?= $this->Html->link($category1->name, $category1->url, ['itemprop' => 'name']); ?></h3>
                        <div class="desc text html" itemprop="description">
                            <?= $this->Content->userHtml($category1->teaser_html); ?>
                        </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </article>
                <?php endif; ?>
                <!-- #Category 1 -->

                <!-- Category 1 -->
                <?php if ($category3): ?>
                    <article class="subcategory subcategory3" itemscope itemtype="http://schema.org/Product">
                        <div class="image">
                            <div class="inside">
                            <?php if ($category3->preview_image_file): ?>
                                <?= $this->Media->thumbnail($category3->preview_image_file->filepath, ['width' => 375, 'height' => 300], [
                                    'url' => $category3->url,
                                    'itemprop' => 'image'
                                ]); ?>
                            <?php endif; ?>
                            </div>
                        </div>
                        <div class="teaser">
                            <div class="inside">
                            <h3 class="title"><?= $this->Html->link($category3->name, $category3->url, ['itemprop' => 'name']); ?></h3>
                            <div class="desc text html" itemprop="description">
                                <?= $this->Content->userHtml($category3->teaser_html); ?>
                            </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </article>
                <?php endif; ?>
                <!-- #Category 1 -->
            </div>
            <div class="col col2">

                <!-- Category 2 -->
                <?php if ($category2): ?>
                    <article class="subcategory subcategory2" itemscope itemtype="http://schema.org/Product">
                        <div class="image">
                            <div class="inside">
                            <?php if ($category1->preview_image_file): ?>
                                <?= $this->Media->thumbnail($category2->preview_image_file->filepath, ['width' => 375, 'height' => 300], [
                                    'url' => $category2->url,
                                    'itemprop' => 'image'
                                ]); ?>
                            <?php endif; ?>
                            </div>
                        </div>
                        <div class="teaser">
                            <div class="inside">
                            <h3 class="title"><?= $this->Html->link($category2->name, $category2->url, ['itemprop' => 'name']); ?></h3>
                            <div class="desc text html" itemprop="description">
                                <?= $this->Content->userHtml($category2->teaser_html); ?>
                            </div>
                            </div>
                        </div>
                    </article>
                <?php endif; ?>
                <!-- #Category 2 -->
            </div>
            <div class="clearfix"></div>
        </div>

    <?php endfor; ?>
</div>
