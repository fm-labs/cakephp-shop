<?php

namespace Shop\Sitemap;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

class SitemapListener implements EventListenerInterface
{
    /**
     * Implemented events
     */
    public function implementedEvents()
    {
        return [
            'Seo.Sitemap.get' => 'getSitemap',
            'Sitemap.get' => 'getSitemap'
        ];
    }

    public function getSitemap(Event $event)
    {
        // Shop categories
        $ShopCategories = TableRegistry::get('Shop.ShopCategories');
        $ShopCategories->addBehavior('Seo.Sitemap', ['fields' => ['loc' => 'url', 'lastmod' => 'modified']]);
        $event->subject()->add($ShopCategories->find('published')->find('sitemap')->toArray(), 'shop_categories');

        // Shop products
        $ShopCategories = TableRegistry::get('Shop.ShopProducts');
        $ShopCategories->addBehavior('Seo.Sitemap', ['fields' => ['loc' => 'url', 'lastmod' => 'modified']]);
        $event->subject()->add($ShopCategories->find('published')->find('sitemap')->toArray(), 'shop_products');
    }
}
