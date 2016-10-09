<?php

namespace Shop\View;

use Content\View\ContentView;
use Cake\I18n\I18n;
use Cake\Routing\Router;

class ShopCategoryView extends ContentView
{

    public function render($view = null, $layout = null)
    {
        if ($this->get('shopCategory')) {
            $shopCategory = $this->get('shopCategory');

            $metaTitle = ($shopCategory->meta_title) ?: $shopCategory->name;
            $shopCategoryUrl = $this->Html->Url->build($shopCategory->url, true);

            // shopCategory title
            $this->assign('title', $metaTitle);

            // canonical url
            $this->Html->meta(['link' => $shopCategoryUrl, 'rel' => 'canonical'], null, ['block' => true]);

            // meta tags
            $metaLang = ($shopCategory->meta_lang) ?: I18n::locale();
            $this->Html->meta(['name' => 'language', 'content' => $metaLang], null, ['block' => true]);

            $metaRobots = 'index,follow';
            $this->Html->meta(['name' => 'robots', 'content' => $metaRobots], null, ['block' => true]);

            $metaDescription = ($shopCategory->meta_desc) ?: $metaTitle;
            $this->Html->meta(['name' => 'description', 'content' => $metaDescription, 'lang' => $metaLang], null, ['block' => true]);

            $metaKeywords = ($shopCategory->meta_keywords) ?: $metaTitle;
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
            $this->Html->meta(['property' => 'og:url', 'content' => $shopCategoryUrl], null, ['block' => true]);

            // Twitter Tags
            $this->Html->meta(['property' => 'twitter:card', 'content' => 'summary'], null, ['block' => true]);
            $this->Html->meta(['property' => 'twitter:title', 'content' => $metaTitle], null, ['block' => true]);
            $this->Html->meta(['property' => 'twitter:description', 'content' => $metaDescription], null, ['block' => true]);
            $this->Html->meta(['property' => 'twitter:url', 'content' => $shopCategoryUrl], null, ['block' => true]);


            // bread crumbs
            $path = $shopCategory->getPath($shopCategory->id)->toArray();
            array_walk($path, function($category) {
               $this->Content->addCrumb($category->name, $category->url);
            });
        }

        return parent::render($view, $layout);
    }
}
