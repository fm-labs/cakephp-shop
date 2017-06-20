<?php
use Cake\Core\Configure;
?>
<div class="product-info-item product-info-price" itemprop="price">
    <?php if (Configure::read('Shop.Price.requireAuth') && !$this->request->session()->read('Shop.Customer.id')): ?>
        <div class="alert alert-info">
            <strong><i class="fa fa-lock"></i>&nbsp;<?= __d('shop','Price is only available for logged in customers'); ?></strong>
            <p><?php
                $url = ['_name' => 'user:login', 'goto' => urlencode($this->Html->Url->build($shopProduct->url))];
                echo $this->Html->link(__d('shop','Please login to see prices'), $url, ['class' => 'btn btn-default']); ?></p>
        </div>
    <?php elseif ($shopProduct->is_buyable): ?>

        <?php if ($shopProduct->price > 0): ?>
            <div class="price price-big" itemprop="price">
                <?= __d('shop','Price'); ?>: <span class="price-item"><?= $this->Number->currency($shopProduct->displayPrice, 'EUR'); ?></span>
            </div>
        <?php elseif ($shopProduct->price == -1): ?>
            <div class="price" itemprop="price">
                Preis auf Anfrage
            </div>
        <?php endif; ?>

        User Price: <?= $shopProduct->user_price_net; ?>


    <?php endif; ?>
</div>