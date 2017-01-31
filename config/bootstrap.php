<?php
use Banana\Lib\ClassRegistry;
use Cake\Core\Plugin;
use Backend\Lib\Backend;
use Content\Lib\ContentManager;

if (Plugin::loaded('Backend')) {
    Backend::hookPlugin('Shop');
}


if (Plugin::loaded('Banana')) {
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
} else {
    trigger_error("Plugin banana not loaded");
}

//\Cake\Event\EventManager::instance()->on(new \Shop\Event\DebugListener());
\Cake\Event\EventManager::instance()->on(new \Shop\Event\CartListener());
\Cake\Event\EventManager::instance()->on(new \Shop\Event\CheckoutListener());