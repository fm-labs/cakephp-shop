<?php
use Banana\Lib\ClassRegistry;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Backend\Lib\Backend;
use Content\Lib\ContentManager;

/**
 * Check dependencies
 */
if (!Plugin::loaded('Banana')) {
    throw new \Cake\Core\Exception\MissingPluginException(['plugin' => 'Banana']);
}
if (!Plugin::loaded('Content')) {
    throw new \Cake\Core\Exception\MissingPluginException(['plugin' => 'Content']);
}

/**
 * Load default config
 */
Configure::load('Shop.shop');

/**
 * Register classes
 */
ClassRegistry::register('PostType', [
    'shop_category' => 'Shop\Model\Entity\Post\ShopCategoryPostType'
]);

ClassRegistry::register('NodeType', [
    'shop_category' => 'Shop\Model\Entity\Node\ShopCategoryNodeType',
]);

ClassRegistry::register('ContentModule', [
    'shop_random_category_product' => 'Shop\View\Cell\RandomCategoryProductModuleCell'
]);

// @deprecated
ClassRegistry::register('PageType', [
    'shop_category' => 'Shop\Page\ShopCategoryPageType'
]);

/**
 * Register events
 */
//\Cake\Event\EventManager::instance()->on(new \Shop\Event\DebugListener());
//\Cake\Event\EventManager::instance()->on(new \Shop\Backend\ShopBackend());
\Cake\Event\EventManager::instance()->on(new \Shop\Event\CartListener());
\Cake\Event\EventManager::instance()->on(new \Shop\Event\CustomerListener());