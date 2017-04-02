<div class="image product-image">
    <?php if ($shopProduct->featured_image_file): ?>
        <?php
        $img = $this->Html->image($shopProduct->featured_image_file->url, ['itemprop' => 'image']);
        //echo $this->Html->link($img, ['action' => 'view', $shopProduct->id], ['escape' => false]);
        echo $img;
        ?>
    <?php endif; ?>
</div>