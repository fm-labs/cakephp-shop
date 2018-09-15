<?php

namespace Shop\Mailer;

use Cake\Core\Configure;
use Cake\Mailer\Email;
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
     * @param Email|null $email
     */
    public function __construct(Email $email = null)
    {
        parent::__construct($email);

        if (Configure::check('Shop.Email.profile')) {
            $this->_email->profile(Configure::read('Shop.Email.profile'));
        }
    }

    /**
     * @param ShopOrder $order
     * @return void
     */
    public function orderSubmission(ShopOrder $order)
    {
        if (!$order->shop_customer || !$order->shop_customer->user) {
            return;
        }

        $this
            ->subject("Ihre Bestellung " . $order->nr_formatted) //@TODO i18n
            ->to($order->shop_customer->user->email)
            ->template('Shop.customer/order_submit')
            ->viewVars(['order' => $order]);
    }

    /**
     * @param ShopOrder $order
     * @return void
     */
    public function orderConfirmation(ShopOrder $order)
    {
        if (!$order->shop_customer || !$order->shop_customer->user) {
            return;
        }

        $this
            ->subject("Ihre Bestellung " . $order->nr_formatted) //@TODO i18n
            ->to($order->shop_customer->user->email)
            ->template('Shop.customer/order_submit')
            ->viewVars(['order' => $order]);
    }

}