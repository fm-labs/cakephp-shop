<?php
use Cake\Core\Configure;

/** @var \Shop\Model\Entity\ShopProduct $shopProduct */
$shopProduct = $this->get('shopProduct');
?>
<div class="product-info-item product-info-price" itemprop="price">
    <?php if (Configure::read('Shop.Price.requireAuth') && !$this->request->getSession()->read('Shop.Customer.id')): ?>
        <div class="alert alert-info">
            <strong><i class="fa fa-lock"></i>&nbsp;<?= __d('shop','Price is only available for logged in customers'); ?></strong>
            <p><?php
                $url = ['_name' => 'user:login', '?' => [
                        'redirect' => urlencode($this->Html->Url->build($shopProduct->url))
                ]];
                echo $this->Html->link(__d('shop','Please login to see prices'), $url, ['class' => 'btn btn-default']); ?></p>
        </div>
    <?php elseif ($shopProduct->is_buyable): ?>

        <?php if ($shopProduct->price >= 0): ?>
            <div class="price price-big" itemprop="price">
                <span class="price-title fw-bold d-block"><?= __d('shop','Price'); ?></span>
                <span class="price-item"><?= $this->Number->currency($shopProduct->displayPrice, 'EUR'); ?></span>
            </div>
            <?php if ($shopProduct->price_net_original != $shopProduct->price_net) : ?>
                <div class="price">
                    <span style="font-size: 90%">statt</span>
                    <small style="font-size: 90%; text-decoration: line-through;"><?= $this->Number->currency($shopProduct->price_net_original, 'EUR'); ?></small>
                </div>
            <?php endif; ?>
        <?php elseif ($shopProduct->price == -1): ?>
            <div class="price">
                Preis auf Anfrage
            </div>
        <?php else : ?>
            <div class="price">
                Preis nicht verfügbar
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>