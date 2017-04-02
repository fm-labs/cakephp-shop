<?php
use Cake\Core\Configure;
?>
<div class="product-info-item product-info-price" itemprop="price" style="text-align: right;">
    <?php if (Configure::read('Shop.Price.requireAuth') && !$this->request->session()->read('Shop.Customer.id')): ?>
        <span><?= __('Price is only available for logged in customers'); ?></span>
    <?php elseif ($shopProduct->is_buyable): ?>
        <span style="font-size: 3em; font-weight: bold;">
            <?= $this->Number->currency($shopProduct->price, 'EUR'); ?>
        </span>
    <?php endif; ?>
</div>