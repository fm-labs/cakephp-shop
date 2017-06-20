<?php

namespace Shop\Mailer;

use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Shop\Model\Entity\ShopOrder;

/**
 * Class OwnerMailer
 *
 * @package Shop\Mailer
 */
class OwnerMailer extends Mailer
{
    /**
     * @param Email|null $email
     */
    public function __construct(Email $email = null)
    {
        parent::__construct($email);

        //@todo automatically setup merchant email configuration, if not configured
        // fallback to 'owner' config
        $profile = (Configure::check('Shop.Email.merchantProfile')) ?: 'owner';
        $this->_email->profile($profile);
    }

    /**
     * @param ShopOrder $order
     * @return array
     */
    public function notifyOrderSubmission(ShopOrder $order)
    {
        return $this
            ->subject("Neue Webshop Bestellung " . $order->nr_formatted) //@TODO i18n
            ->template('Shop.merchant/order_submit')
            ->viewVars(['order' => $order])
            ->send();
    }

    /**
     * @param ShopOrder $order
     * @return array
     */
    public function notifyOrderConfirmation(ShopOrder $order)
    {
        return $this
            ->subject("Neue Webshop Bestellung " . $order->nr_formatted) //@TODO i18n
            ->template('Shop.merchant/order_submit')
            ->viewVars(['order' => $order])
            ->send();
    }
}
