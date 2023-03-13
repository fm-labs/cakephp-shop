<?php
namespace Shop\Mailer\Preview;

use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;
use DebugKit\Mailer\MailPreview;
use Shop\Mailer\ShopCustomerMailer;
use User\Mailer\UserMailer;

/**
 * @property \User\Model\Table\UsersTable $Users
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 * @property \Shop\Model\Table\ShopOrderAddressesTable $ShopOrderAddresses
 */
class ShopMailPreviewBase extends MailPreview
{
    /**
     * @return \User\Model\Entity\User
     */
    protected function getPreviewUser(): \User\Model\Entity\User
    {
        $this->Users = TableRegistry::getTableLocator()->get('User.Users');
        /** @var \User\Model\Entity\User $user */
        //$user = $this->Users->find()->first();
        $user = $this->Users->newEmptyEntity();
        $user->locale = "de";
        $user->username = "testuser";
        $user->email = "test@example.org";
        $user->email_verification_required = true;
        $user->email_verification_code = "dummy-verification-code";
        $user->password_reset_code = "dummy-reset-code";

        return $user;
    }

    /**
     * @return \Shop\Model\Entity\ShopOrder
     */
    protected function getPreviewOrder(): \Shop\Model\Entity\ShopOrder
    {
        $this->ShopOrders = TableRegistry::getTableLocator()->get('Shop.ShopOrders');
        $this->ShopOrderAddresses = TableRegistry::getTableLocator()->get('Shop.ShopOrderAddresses');

        /** @var \Shop\Model\Entity\ShopAddress $address */
        $address = $this->ShopOrderAddresses->newEmptyEntity();
        $address->first_name = "First";
        $address->last_name = "Last";
        $address->street1 = "Street 1";
        $address->street2 = "Street 2";
        $address->zipcode = "1234";
        $address->city = "Cybercity";
        $address->country_iso2 = "at";

        /** @var \Shop\Model\Entity\ShopOrder $order */
        $order = $this->ShopOrders->newEmptyEntity();
        $order->id = 999999999;
        $order->uuid = "asdf-werwer-asdfasdf-2rewerewsfd";
        $order->shop_customer = $this->ShopOrders->ShopCustomers->newEmptyEntity();
        $order->shop_customer->id = 99999999;
        $order->shop_customer->user = $this->getPreviewUser();
        $order->shop_customer->email = "test@example.org";
        $order->order_value_total = 1000;
        $order->billing_address = $address;
        $order->shipping_address = $address;
        $order->payment_type = "mpay24";
        return $order;
    }
}
