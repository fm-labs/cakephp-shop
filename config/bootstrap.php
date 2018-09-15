<?php
use Banana\Lib\ClassRegistry; //@TODO Remove dependency. Use event system instead
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Log\Log;

/**
 * Check dependencies
 */
//if (!Plugin::loaded('Banana')) {
//    throw new \Cake\Core\Exception\MissingPluginException(['plugin' => 'Banana']);
//}
//if (!Plugin::loaded('Content')) {
//    throw new \Cake\Core\Exception\MissingPluginException(['plugin' => 'Content']);
//}

/**
 * Log configuration
 */
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
Configure::load('Shop.content');
Configure::load('Shop.shop');

/**
 * Register classes
 * @deprecated
 */
ClassRegistry::register('PostType', [
    'shop_category' => '\Shop\Model\Entity\Post\ShopCategoryPostType'
]);

ClassRegistry::register('ContentModule', [
    'shop_random_category_product' => '\Shop\View\Cell\RandomCategoryProductModuleCell'
]);

ClassRegistry::register('PageType', [
    'shop_category' => '\Shop\Page\ShopCategoryPageType'
]);
