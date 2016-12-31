<?php

namespace Shop\Sitemap;

use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;
use Sitemap\Lib\ModelSitemapProvider;

class ShopProductsSitemapProvider extends ModelSitemapProvider
{
    public $modelClass = 'Shop.ShopProducts';

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
        $locations = [];
        foreach ($result as $product) {
            $locations[] = [ 'url' => $product->url];
        }

        return $locations;
    }
}