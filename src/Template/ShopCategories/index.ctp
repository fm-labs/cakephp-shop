<?php $this->extend('extend_view'); ?>
<div class="shop categories view threecol subgrid">
    <div class="ui three column grid">
        <?php foreach($shopCategory->subcategories as $subCategory): ?>
        <div class="column">
            <div class="subcategory">
                <div class="image">
                    <?php if ($subCategory->preview_image_file): ?>
                        <?php
                        $img = $this->Html->image($subCategory->preview_image_file->url);
                        echo $this->Html->link($img, $subCategory->url, ['escape' => false]);
                        ?>
                    <?php endif; ?>
                </div>
                <h1><?= $this->Html->link($subCategory->name, $subCategory->url); ?></h1>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>