<?php
namespace Shop\Mailer\Preview;

use Shop\Mailer\ShopCustomerMailer;

/**
 * @property \User\Model\Table\UsersTable $Users
 */
class ShopCustomerMailPreview extends ShopMailPreviewBase
{
    public function orderSubmission(): ShopCustomerMailer
    {
        /** @var ShopCustomerMailer $mailer */
        $mailer = $this->getMailer("Shop.ShopCustomer");
        $order = $this->getPreviewOrder();
        return $mailer
            ->orderSubmission($order);
    }

    public function orderConfirmation(): ShopCustomerMailer
    {
        /** @var ShopCustomerMailer $mailer */
        $mailer = $this->getMailer("Shop.ShopCustomer");
        $order = $this->getPreviewOrder();
        return $mailer
            ->orderConfirmation($order);
    }

}
