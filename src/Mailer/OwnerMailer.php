<?php
declare(strict_types=1);

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
     * @param \Cake\Mailer\Email|null $email
     */
    public function __construct()
    {
        parent::__construct(Configure::read('Shop.Email.merchantProfile'));
    }

    /**
     * @param \Shop\Model\Entity\ShopOrder $order
     * @return void
     */
    public function orderSubmissionNotify(ShopOrder $order)
    {
        $this
            ->setSubject("Neue Webshop Bestellung " . $order->nr_formatted) //@TODO i18n
            ->setViewVars(['order' => $order])
            ->viewBuilder()
                ->setTemplate('Shop.merchant/order_submit')
        ;
    }

    /**
     * @param \Shop\Model\Entity\ShopOrder $order
     * @return void
     */
    public function orderConfirmationNotify(ShopOrder $order)
    {
        $this
            ->setSubject("Neue Webshop Bestellung " . $order->nr_formatted) //@TODO i18n
            ->setViewVars(['order' => $order])
            ->viewBuilder()
            ->setTemplate('Shop.merchant/order_submit')
        ;
    }
}
