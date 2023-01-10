<?php
declare(strict_types=1);

namespace Shop\Sitemap;

use Cake\ORM\TableRegistry;
use Seo\Sitemap\SitemapProviderInterface;
use Seo\Sitemap\SitemapUrl;

class ShopCategorySitemapProvider implements SitemapProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        $ShopCategories = TableRegistry::getTableLocator()->get('Shop.ShopCategories');
        foreach ($ShopCategories->find()->find('published') as $category) {
            yield new SitemapUrl($category->getViewUrl(), 0.5, $category->modified);
        }
    }
}
