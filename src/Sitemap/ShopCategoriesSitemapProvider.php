<?php

namespace Shop\Sitemap;

use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;
use Sitemap\Lib\ModelSitemapProvider;

class ShopCategoriesSitemapProvider extends ModelSitemapProvider
{
    public $modelClass = 'Shop.ShopCategories';

    public function find(Query $query)
    {
        $query
        ->find('published')
        ->contain([]);

        return $query;
    }

    public function compile(ResultSetInterface $result)
    {
        $locations = [];
        foreach ($result as $category) {
            $locations[] = [ 'url' => $category->url];
        }

        return $locations;
    }
}