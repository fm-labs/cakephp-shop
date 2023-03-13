<?php
declare(strict_types=1);

namespace Shop\Mailer;

use Cake\Core\Configure;
use Cake\Mailer\Mailer;
use Shop\Model\Entity\ShopOrder;
use User\Mailer\UserMailer;

/**
 * Class CustomerMailer
 *
 * @package Shop\Mailer
 */
class ShopCustomerMailer extends UserMailer
{
    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct();

        if (Configure::read('Shop.Mailer.customerProfile')) {
            $this->setProfile(Configure::read('Shop.Mailer.customerProfile'));
        }
    }

    /**
     * @param \Shop\Model\Entity\ShopOrder $order
     * @return ShopCustomerMailer
     */
    public function orderSubmission(ShopOrder $order): ShopCustomerMailer
    {
        if (!$order->shop_customer || !$order->shop_customer->user) {
            throw new \LogicException("Order has no customer or user");
        }

        $orderViewUrl = \Cake\Routing\Router::url([
            'plugin' => 'Shop',
            'controller' => 'ShopOrders',
            'action' => 'view',
            'prefix' => null,
            $order->uuid,
            '?' => ['ref' => 'email']
        ], true);

        $this
            ->setSubject("Ihre Bestellung " . $order->nr_formatted) //@TODO i18n
            ->setUser($order->shop_customer->user)
            ->setViewVars(['order' => $order])
            ->viewBuilder()
                ->setTemplate('Shop.customer/order_submit')
               ->setVar('viewUrl', $orderViewUrl)
        ;
        return $this;
    }

    /**
     * @param \Shop\Model\Entity\ShopOrder $order
     * @return ShopCustomerMailer
     */
    public function orderConfirmation(ShopOrder $order): ShopCustomerMailer
    {
        if (!$order->shop_customer || !$order->shop_customer->user) {
            throw new \LogicException("Order has no customer or user");
        }

        $orderViewUrl = \Cake\Routing\Router::url([
            'plugin' => 'Shop',
            'controller' => 'ShopOrders',
            'action' => 'view',
            'prefix' => null,
            $order->uuid,
            '?' => ['ref' => 'email']
        ], true);

        $this
            ->setSubject("Bestätigung Ihrer Bestellung " . $order->nr_formatted) //@TODO i18n
            ->setUser($order->shop_customer->user)
            ->setViewVars(['order' => $order])
            ->viewBuilder()
                ->setTemplate('Shop.customer/order_confirm')
                ->setVar('viewUrl', $orderViewUrl)
        ;
        return $this;
    }
}
