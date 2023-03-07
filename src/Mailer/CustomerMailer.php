<?php
declare(strict_types=1);

namespace Shop\Mailer;

use Cake\Core\Configure;
use Cake\Mailer\Mailer;
use Shop\Model\Entity\ShopOrder;

/**
 * Class CustomerMailer
 *
 * @package Shop\Mailer
 */
class CustomerMailer extends Mailer
{
    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct(Configure::read('Shop.Email.profile'));
    }

    /**
     * @param \Shop\Model\Entity\ShopOrder $order
     * @return void
     */
    public function orderSubmission(ShopOrder $order)
    {
        if (!$order->shop_customer || !$order->shop_customer->user) {
            return;
        }

        $this
            ->setSubject("Ihre Bestellung " . $order->nr_formatted) //@TODO i18n
            ->setTo($order->shop_customer->user->email)
            ->setViewVars(['order' => $order])
            ->viewBuilder()
                ->setTemplate('Shop.customer/order_submit');
    }

    /**
     * @param \Shop\Model\Entity\ShopOrder $order
     * @return void
     */
    public function orderConfirmation(ShopOrder $order)
    {
        if (!$order->shop_customer || !$order->shop_customer->user) {
            return;
        }

        $this
            ->setSubject("Bestätigung Ihrer Bestellung " . $order->nr_formatted) //@TODO i18n
            ->setTo($order->shop_customer->user->email)
            ->setViewVars(['order' => $order])
            ->viewBuilder()
                ->setTemplate('Shop.customer/order_confirm');
    }
}
