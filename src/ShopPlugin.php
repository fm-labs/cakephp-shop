<?php
declare(strict_types=1);

namespace Shop;

use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\Routing\RouteBuilder;
use Cupcake\Model\EntityTypeRegistry;
use Shop\Model\Entity\Menu\ShopCategoryMenuType;

/**
 * Class ShopPlugin
 *
 * @package Shop
 */
class ShopPlugin extends BasePlugin
{
    public $bootstrapEnabled = true;

    public $routesEnabled = true;

    public function bootstrap(PluginApplicationInterface $app): void
    {
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

        $app->addPlugin('Content');
        $app->addPlugin('Media');
        //$app->addOptionalPlugin('Admin');
        $app->addOptionalPlugin('Settings');
        $app->addOptionalPlugin('Seo');
        $app->addOptionalPlugin('Cron');

        /**
         * Load default config
         */
        Configure::load('Shop.shop');
        //Configure::load('Shop.content');
        Configure::load('Shop.html_editor');

        if (\Cake\Core\Plugin::isLoaded('Settings')) {
            Configure::load('Shop', 'settings');
        }

        /**
         * Services
         */
        $eventManager = EventManager::instance();
        $eventManager->on(new \Shop\Service\CartService());
        $eventManager->on(new \Shop\Service\CustomerService());
        $eventManager->on(new \Shop\Service\EmailNotificationService());
        $eventManager->on(new \Shop\Service\OrderNotificationService());
        $eventManager->on(new \Shop\Service\PaymentService());
        $eventManager->on(new \Shop\Service\CustomerDiscountService());
        $eventManager->on(new \Shop\Service\HealthCheckService());
        //$eventManager->on(new \Shop\Service\SitemapService());
        EntityTypeRegistry::register('Content.Menu', 'shop_category', [
            'label' => __d('shop', 'Shop Category'),
            'className' => ShopCategoryMenuType::class,
        ]);

        /**
         * Admin Plugin
         */
        if (\Cake\Core\Plugin::isLoaded('Admin')) {
            \Admin\Admin::addPlugin(new \Shop\ShopAdmin());
        }

        /**
         * Seo plugin
         */
        if (\Cake\Core\Plugin::isLoaded('Seo')) {
            \Seo\Sitemap\Sitemap::setConfig('shop_categories', [
                'className' => 'Shop.ShopCategorySitemap',
            ]);
            \Seo\Sitemap\Sitemap::setConfig('shop_products', [
                'className' => 'Shop.ShopProductSitemap',
            ]);
        }

        /**
         * DebugKit
         */
        if (\Cake\Core\Plugin::isLoaded('DebugKit')) {
            $panels = Configure::read('DebugKit.panels', []);
            $panels['Shop.Shop'] = true;
            Configure::write('DebugKit.panels', $panels);
        }

//        /**
//         * Cron plugin
//         */
//        if (\Cake\Core\Plugin::isLoaded('Cron')) {
//            if (!\Cron\Cron::getConfig('shop_clear_cc_data')) {
//                \Cron\Cron::setConfig('shop_clear_cc_data', [
//                    'className' => \Shop\Cron\Task\ClearCreditcardDataCronTask::class,
//                    'interval' => 'weekly'
//                ]);
//            }
//        }
    }

    public function routes(RouteBuilder $routes): void
    {
        parent::routes($routes);
    }
}
