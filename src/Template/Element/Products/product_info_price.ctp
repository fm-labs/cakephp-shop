<?php
use Cake\Core\Configure;
?>
<div class="product-info-item product-info-price" itemprop="price" style="text-align: right;">
    <?php if (Configure::read('Shop.Price.requireAuth') && !$this->request->session()->read('Shop.Customer.id')): ?>
        <span><?= __('Price is only available for logged in customers'); ?></span>
    <?php elseif ($shopProduct->is_buyable): ?>

        <?php if ($shopProduct->price > 0): ?>
            <div class="price price-big" itemprop="price">
                <?= $this->Number->currency($shopProduct->price, 'EUR'); ?>
            </div>
        <?php elseif ($shopProduct->price == -1): ?>
            <div class="price" itemprop="price">
                Preis auf Anfrage
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>