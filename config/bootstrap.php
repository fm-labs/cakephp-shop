<?php
use Cupcake\Lib\ClassRegistry;
use Cake\Core\Configure;
use Cake\Log\Log;

/**
 * Check dependencies
 */
//if (!Plugin::isLoaded('Cupcake')) {
//    throw new \Cake\Core\Exception\MissingPluginException(['plugin' => 'Cupcake']);
//}
//if (!Plugin::isLoaded('Content')) {
//    throw new \Cake\Core\Exception\MissingPluginException(['plugin' => 'Content']);
//}

/**
 * Log configuration
 */
if (!Log::getConfig('shop')) {
    Log::setConfig('shop', [
        'className' => 'Cake\Log\Engine\FileLog',
        'path' => LOGS,
        'file' => 'shop',
        //'levels' => ['notice', 'info', 'debug'],
        'scopes' => ['shop', 'order', 'payment', 'invoice', 'checkout'],
    ]);
}

/**
 * Load default config
 */
//Configure::load('Shop.content');
Configure::load('Shop.html_editor');
//Configure::load('Shop.shop');

/**
 * Register classes
 * @deprecated
 */
//ClassRegistry::register('ContentModule', [
//    'shop_random_category_product' => '\Shop\View\Cell\RandomCategoryProductModuleCell',
//]);
//ClassRegistry::register('PostType', [
//    'shop_category' => '\Shop\Model\Entity\Post\ShopCategoryPostType',
//]);
//ClassRegistry::register('PageType', [
//    'shop_category' => '\Shop\Page\ShopCategoryPageType',
//]);
