<?php
declare(strict_types=1);

namespace Shop\Sitemap;

use Cake\ORM\TableRegistry;
use Seo\Sitemap\SitemapProviderInterface;
use Seo\Sitemap\SitemapUrl;

class ShopProductSitemapProvider implements SitemapProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getIterator(): \Generator|iterable
    {
        $ShopProducts = TableRegistry::getTableLocator()->get('Shop.ShopProducts');
        foreach ($ShopProducts->find()->where(['ShopProducts.type' => 'parent'])->find('published') as $product) {
            yield new SitemapUrl($product->getViewUrl(), 0.5, $product->modified);
        }
    }
}
