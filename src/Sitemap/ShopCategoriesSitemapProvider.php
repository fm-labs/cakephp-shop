<?php

namespace Shop\Sitemap;

use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;
use Sitemap\Sitemap\AbstractTableSitemapProvider;
use Sitemap\Sitemap\SitemapLocation;

class ShopCategoriesSitemapProvider extends AbstractTableSitemapProvider
{
    public $modelClass = 'Shop.ShopCategories';

    public $name = 'shop_categories';

    public function find(Query $query)
    {
        $query
        ->find('published')
        ->contain([]);

        return $query;
    }

    public function compile(ResultSetInterface $result)
    {
        foreach ($result as $category) {
            $this->_addLocation(new SitemapLocation($category->url, null, $category->modified));
        }
    }
}