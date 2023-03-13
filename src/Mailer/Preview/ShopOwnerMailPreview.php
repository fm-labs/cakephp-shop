<?php
namespace Shop\Mailer\Preview;

use Shop\Mailer\ShopOwnerMailer;

/**
 * @property \User\Model\Table\UsersTable $Users
 */
class ShopOwnerMailPreview extends ShopMailPreviewBase
{
    public function orderSubmissionNotify(): ShopOwnerMailer
    {
        /** @var ShopOwnerMailer $mailer */
        $mailer = $this->getMailer("Shop.ShopOwner");
        $order = $this->getPreviewOrder();
        return $mailer
            ->orderSubmissionNotify($order);
    }

    public function orderConfirmationNotify(): ShopOwnerMailer
    {
        /** @var ShopOwnerMailer $mailer */
        $mailer = $this->getMailer("Shop.ShopOwner");
        $order = $this->getPreviewOrder();
        return $mailer
            ->orderConfirmationNotify($order);
    }

}
