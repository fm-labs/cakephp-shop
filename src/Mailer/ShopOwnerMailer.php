<?php
declare(strict_types=1);

namespace Shop\Mailer;

use Cake\Core\Configure;
use Cake\Mailer\Mailer;
use Shop\Model\Entity\ShopOrder;

/**
 * Class OwnerMailer
 *
 * @package Shop\Mailer
 */
class ShopOwnerMailer extends Mailer
{
    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct(Configure::read('Shop.Mailer.merchantProfile'));
    }

    /**
     * @param \Shop\Model\Entity\ShopOrder $order
     * @return ShopOwnerMailer
     */
    public function orderSubmissionNotify(ShopOrder $order): ShopOwnerMailer
    {
        $orderAdminViewUrl = \Cake\Routing\Router::url([
            'plugin' => 'Shop',
            'controller' => 'ShopOrders',
            'action' => 'view',
            'prefix' => 'Admin',
            $order->id,
            '?' => ['ref' => 'email']
        ], true);

        $this
            ->setSubject("Neue Webshop Bestellung " . $order->nr_formatted) //@TODO i18n
            ->setViewVars(['order' => $order])
            ->viewBuilder()
                ->setLayout('Shop.merchant')
                ->setTemplate('Shop.merchant/order_submit')
                ->setVar('adminViewUrl', $orderAdminViewUrl)
        ;
        return $this;
    }

    /**
     * @param \Shop\Model\Entity\ShopOrder $order
     * @return ShopOwnerMailer
     */
    public function orderConfirmationNotify(ShopOrder $order): ShopOwnerMailer
    {
        $orderAdminViewUrl = \Cake\Routing\Router::url([
            'plugin' => 'Shop',
            'controller' => 'ShopOrders',
            'action' => 'view',
            'prefix' => 'Admin',
            $order->id,
            '?' => ['ref' => 'email']
        ], true);

        $this
            ->setSubject("Neue Webshop Bestellung " . $order->nr_formatted) //@TODO i18n
            ->setViewVars(['order' => $order])
            ->viewBuilder()
                ->setLayout('Shop.merchant')
                ->setTemplate('Shop.merchant/order_submit')
                ->setVar('adminViewUrl', $orderAdminViewUrl)
        ;
        return $this;
    }
}
