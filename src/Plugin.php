<?php
declare(strict_types=1);

namespace Shop;

use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventManager;
use Cupcake\Model\EntityTypeRegistry;

/**
 * Class ShopPlugin
 *
 * @package Shop
 */
class Plugin extends BasePlugin
{
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        $app->addPlugin('Content');
        $app->addPlugin('Media');

        $eventManager = EventManager::instance();
        $eventManager->on(new \Shop\Service\CartService());
        $eventManager->on(new \Shop\Service\CustomerService());
        $eventManager->on(new \Shop\Service\EmailNotificationService());
        $eventManager->on(new \Shop\Service\OrderService());
        $eventManager->on(new \Shop\Service\OrderNotificationService());
        $eventManager->on(new \Shop\Service\PaymentService());
        $eventManager->on(new \Shop\Service\ShopRulesService());
        $eventManager->on(new \Shop\Sitemap\SitemapListener());

        EntityTypeRegistry::register('Content.Menu', 'shop_category', [
            'label' => __('Shop Category'),
            'className' => '\\Content\\Model\\Entity\\Menu\\ShopCategoryMenuType',
        ]);

        /**
         * Register Admin Plugin
         */
        if (\Cake\Core\Plugin::isLoaded('Admin')) {
            \Admin\Admin::addPlugin(new \Shop\Admin());
        }
    }

    public function getConfigurationUrl()
    {
        return \Cake\Core\Plugin::isLoaded('Settings')
            ? ['_name' => 'settings:manage', $this->getName()]
            : null;
    }

}
