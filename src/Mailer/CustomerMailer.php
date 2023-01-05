<?php
declare(strict_types=1);

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
     * @param \Cake\Mailer\Email|null $email
     */
    public function __construct(?Email $email = null)
    {
        parent::__construct($email);

        if (Configure::check('Shop.Email.profile')) {
            $this->_email->setProfile(Configure::read('Shop.Email.profile'));
        }
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
