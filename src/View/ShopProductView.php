<?php
declare(strict_types=1);

namespace Shop\View;

use Cake\I18n\I18n;

/**
 * Class ShopProductView
 *
 * @package Shop\View
 */
class ShopProductView extends ShopView
{
    /**
     * @param null $view
     * @param null $layout
     * @return null|string
     */
    public function render($view = null, $layout = null)
    {
        if ($this->get('shopProduct')) {
            $shopProduct = $this->get('shopProduct');

            $metaTitle = $shopProduct->meta_title ?: $shopProduct->name;
            $shopProductUrl = $this->Html->Url->build($shopProduct->url, true);

            // shopProduct title
            $this->assign('title', $metaTitle);

            // canonical url
            $this->Html->meta(['link' => $shopProductUrl, 'rel' => 'canonical'], null, ['block' => true]);

            // meta tags
            $metaLang = $shopProduct->meta_lang ?: I18n::getLocale();
            $this->Html->meta(['name' => 'language', 'content' => $metaLang], null, ['block' => true]);

            $metaRobots = 'index,follow';
            $this->Html->meta(['name' => 'robots', 'content' => $metaRobots], null, ['block' => true]);

            $metaDescription = $shopProduct->meta_desc ?: $metaTitle;
            $this->Html->meta(['name' => 'description', 'content' => $metaDescription, 'lang' => $metaLang], null, ['block' => true]);

            $metaKeywords = $shopProduct->meta_keywords ?: $metaTitle;
            $this->Html->meta(['name' => 'keywords', 'content' => $metaKeywords, 'lang' => $metaLang], null, ['block' => true]);

            //$this->Html->meta(['name' => 'revisit-after', 'content' => '7 days'], null, ['block' => true]);
            //$this->Html->meta(['name' => 'expires', 'content' => 0], null, ['block' => true]);
            //$this->Html->meta(['name' => 'abstract', 'content' => $metaDescription], null, ['block' => true]);
            //$this->Html->meta(['name' => 'distribution', 'content' => 'global'], null, ['block' => true]);
            //$this->Html->meta(['name' => 'generator', 'content' => 'Banana Cake x.x.x'], null, ['block' => true]);
            //$this->Html->meta(['name' => 'googlebot', 'content' => ''], null, ['block' => true]);
            //$this->Html->meta(['name' => 'no-email-collection', 'content' => 'http://www.metatags.nl/nospamharvesting'], null, ['block' => true]);
            //$this->Html->meta(['name' => 'rating', 'content' => 'general'], null, ['block' => true]);
            //$this->Html->meta(['name' => 'reply-to', 'content' => 'webmaster@exmaple.org'], null, ['block' => true]);

            //$this->Html->meta(['http-equiv' => 'cache-control', 'content' => 'public'], null, ['block' => true]);
            //$this->Html->meta(['http-equiv' => 'content-type', 'content' => 'text/html'], null, ['block' => true]);
            //$this->Html->meta(['http-equiv' => 'content-language', 'content' => $metaLang], null, ['block' => true]);
            //$this->Html->meta(['http-equiv' => 'pragma', 'content' => 'no-cache'], null, ['block' => true]);

            // Open Graph Tags
            $this->Html->meta(['property' => 'og:type', 'content' => 'website'], null, ['block' => true]);
            $this->Html->meta(['property' => 'og:title', 'content' => $metaTitle], null, ['block' => true]);
            $this->Html->meta(['property' => 'og:description', 'content' => $metaDescription], null, ['block' => true]);
            $this->Html->meta(['property' => 'og:url', 'content' => $shopProductUrl], null, ['block' => true]);

            // Twitter Tags
            $this->Html->meta(['property' => 'twitter:card', 'content' => 'summary'], null, ['block' => true]);
            $this->Html->meta(['property' => 'twitter:title', 'content' => $metaTitle], null, ['block' => true]);
            $this->Html->meta(['property' => 'twitter:description', 'content' => $metaDescription], null, ['block' => true]);
            $this->Html->meta(['property' => 'twitter:url', 'content' => $shopProductUrl], null, ['block' => true]);

            // bread crumbs
            $this->Breadcrumbs->add(__d('shop', 'Shop'), ['_name' => 'shop:index']);
            $path = $shopProduct->getPath($shopProduct->id);
            if ($path) {
                $path = $path->toArray();
                array_walk($path, function ($category) {
                    $this->Breadcrumbs->add($category->name, $category->getUrl());
                });
                $this->Breadcrumbs->add($shopProduct->title);
            }
        }

        return parent::render($view, $layout);
    }
}
