<?php //$this->extend('extend_view'); ?>
<div class="shop categories index container">
    <div class="row">
        <?php $i = 0; ?>
        <?php foreach ($shopCategory->published_subcategories as $category): ?>
            <?php
            $viewUrl = $category->url;
            ?>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="category">
                    <div class="image">
                        <?php if ($category->preview_image_file): ?>
                            <?php
                            $img = $this->Html->image($category->preview_image_file->url);
                            echo $this->Html->link($img, $viewUrl, ['escape' => false]);
                            ?>
                        <?php endif; ?>
                    </div>
                    <h1><?= $this->Html->link($category->name, $viewUrl); ?></h1>
                </div>
            </div>
            <?php echo (++$i % 3 == 0) ? '</div><div class="row">' : ''; echo "\n"; ?>
        <?php endforeach; ?>
    </div>
</div>

