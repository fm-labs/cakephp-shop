<?php

namespace Shop\View;

use Cake\Routing\Exception\MissingRouteException;
use Content\View\ContentView;
use Cake\I18n\I18n;

/**
 * Class ShopCategoryView
 *
 * @package Shop\View
 */
class ShopCategoryView extends ContentView
{

    /**
     * @param null $view
     * @param null $layout
     * @return null|string
     */
    public function render($view = null, $layout = null)
    {
        $metaDescription = $metaKeywords = null;
        $metaRobots = 'index,follow';
        $metaLang = I18n::locale();

        // bread crumbs - shop index
        // @TODO Refactor with event listener
        try {
            $this->Breadcrumbs->add(__d('shop', 'Shop'), ['_name' => 'shop:index']);
        } catch (MissingRouteException $ex) {
            // the named route might not be connected. fallback to ShopController::index
            $this->Breadcrumbs->add(__d('shop', 'Shop'), ['plugin' => 'Shop', 'controller' => 'ShopController', 'action' => 'index']);
        }

        // gather meta info
        // no shop category is set, it's assumed we are on the category index page
        if (!$this->get('shopCategory')) {

            $metaTitle = $this->Blocks->get('heading');
            $metaTitle = ($metaTitle) ?: $this->Blocks->get('title');
            $metaTitle = ($metaTitle) ?: __d('shop','All Categories');
            $shopCategoryUrl = ['plugin' => 'Shop', 'controller' => 'Categories', 'action' => 'index'];

            // breadcrumbs
            $this->Breadcrumbs->add($metaTitle, $shopCategoryUrl);

        } else {

            $shopCategory = $this->get('shopCategory');
            $shopCategoryUrl = $shopCategory->url;

            $metaTitle = ($shopCategory->meta_title) ?: $shopCategory->name;
            $metaLang = ($shopCategory->meta_lang) ?: I18n::locale();
            $metaDescription = ($shopCategory->meta_desc) ?: $metaTitle;
            $metaKeywords = ($shopCategory->meta_keywords) ?: $metaTitle;


            // bread crumbs
            $path = $shopCategory->getPath($shopCategory->id)->toArray();
            array_walk($path, function($category) {
                $this->Breadcrumbs->add($category->name, $category->url);
            });
            //$this->Breadcrumbs->add($shopCategory->name);
        }

        // privacy options
        if ($this->get('_private') || $this->get('_nofollow')) {
            $metaRobots = 'noindex,nofollow';
        }

        $shopCategoryUrl = $this->Html->Url->build($shopCategoryUrl, true);

        // shopCategory title
        $this->assign('title', $metaTitle);

        // canonical url
        $this->Html->meta(['link' => $shopCategoryUrl, 'rel' => 'canonical'], null, ['block' => true]);

        // meta tags
        $this->Html->meta(['name' => 'language', 'content' => $metaLang], null, ['block' => true]);
        $this->Html->meta(['name' => 'robots', 'content' => $metaRobots], null, ['block' => true]);
        $this->Html->meta(['name' => 'description', 'content' => $metaDescription, 'lang' => $metaLang], null, ['block' => true]);
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

        return parent::render($view, $layout);
    }
}
