<?php
declare(strict_types=1);

namespace Shop\Service;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

class SitemapService implements EventListenerInterface
{
    /**
     * Implemented events
     */
    public function implementedEvents(): array
    {
        return [
            //'Seo.Sitemap.build' => 'buildSitemap',
        ];
    }

    public function buildSitemap(Event $event)
    {
        $sitemap = $event->getSubject();

        // Shop categories
        $ShopCategories = TableRegistry::getTableLocator()->get('Shop.ShopCategories');
        $ShopCategories->addBehavior('Seo.Sitemap', ['fields' => ['loc' => 'url', 'lastmod' => 'modified']]);
        //$sitemap->addUrl($ShopCategories->find('published')->find('sitemap')->toArray());

        // Shop products
        $ShopCategories = TableRegistry::getTableLocator()->get('Shop.ShopProducts');
        $ShopCategories->addBehavior('Seo.Sitemap', ['fields' => ['loc' => 'url', 'lastmod' => 'modified']]);
        //$sitemap->addUrl($ShopCategories->find('published')->find('sitemap')->toArray());
    }
}
