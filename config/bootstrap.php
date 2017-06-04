<?php
use Backend\Lib\Backend;
use Banana\Lib\ClassRegistry;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Log\Log;
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

// Mailman log config
if (!Log::config('shop')) {
    Log::config('shop', [
        'className' => 'Cake\Log\Engine\FileLog',
        'path' => LOGS,
        'file' => 'shop',
        //'levels' => ['notice', 'info', 'debug'],
        'scopes' => ['shop', 'order', 'payment', 'invoice', 'checkout']
    ]);
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
