<?php

namespace Shop\Sitemap;

use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;
use Seo\Sitemap\AbstractTableSitemapProvider;
use Seo\Sitemap\SitemapLocation;

class ShopProductsSitemapProvider extends AbstractTableSitemapProvider
{
    public $modelClass = 'Shop.ShopProducts';

    public $name = 'shop_products';

    public function find(Query $query)
    {
        $this->_table->locale('de');
        $this->_table->ShopCategories->locale('de');

        $query
            ->find('published')
            ->find('translations')
            ->contain(['ShopCategories'  => function ($query) {
                return $query->find('translations');
            }]);

        return $query;
    }

    public function compile(ResultSetInterface $result)
    {
        foreach ($result as $product) {
            $this->_addLocation(new SitemapLocation($product->url, null, $product->modified));
        }
    }
}
