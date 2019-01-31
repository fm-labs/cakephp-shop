<?php

namespace Shop\Service;

use Cake\Event\Event;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Shop\Service\BaseService;

/**
 * Class CustomerService
 *
 * @package Shop\Event
 */
class CustomerService extends BaseService
{
    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'User.Auth.login' => 'onUserLogin',
            'User.Auth.logout' => 'onUserLogout',
            'User.Model.User.register' => 'onUserRegister',
            'Shop.Model.Order.afterSubmit' => 'afterOrderSubmit'
            //'Auth.identifyUser' => 'onUserLogin', // <-- Hmm, can't capture this event...
            //'Auth.logout' => 'onUserLogout',
        ];
    }

    /**
     * @param Event $event
     */
    public function onUserRegister(Event $event)
    {
        $user = $event->subject();
        $customer = null;

        try {
            $customer = TableRegistry::get('Shop.ShopCustomers')->createFromUser($user, $event->data());
        } catch (\Exception $ex) {
            Log::error('CustomerEventListener::onUserRegister: ' . $ex->getMessage());
        }

        if ($customer) {
            Log::debug('[shop] Set customer for user ' . $user->id);
            //$event->subject()->request->session()->write('Shop.Customer', $customer->toArray());
        } else {
            Log::alert('[shop] Failed to create customer for user ' . $user->id);
            //$event->subject()->request->session()->delete('Shop.Customer');
        }
    }

    /**
     * @param Event $event
     */
    public function onUserLogin(Event $event)
    {
        // user login detected
        $userId = $event->data['user']['id'];
        if (!$userId) {
            Log::alert('[shop] Login without userId detected');

            return;
        }

        $customer = TableRegistry::get('Shop.ShopCustomers')->find()
            ->where(['ShopCustomers.user_id' => $userId])
            ->contain([])
            ->first();

        // attempt to auto-create customer for user
        if (!$customer) {
            try {
                $customer = TableRegistry::get('Shop.ShopCustomers')->createFromUserId($userId);
            } catch (\Exception $ex) {
                Log::error('CustomerEventListener::onUserLogin: ' . $ex->getMessage());
            }
        }

        if ($customer) {
            Log::debug('[shop] Set customer for user ' . $userId);
            $event->subject()->request->session()->write('Shop.Customer', $customer->toArray());
        } else {
            Log::alert('[shop] Failed to create customer for user ' . $userId);
            $event->subject()->request->session()->delete('Shop.Customer');
        }
    }

    /**
     * @param Event $event
     */
    public function onUserLogout(Event $event)
    {
        $event->subject()->request->session()->delete('Shop.Customer');
        $event->subject()->request->session()->delete('Shop.Order');
        $event->subject()->request->session()->delete('Shop.Cart');
        $event->subject()->request->session()->delete('Shop.Checkout');
    }

    /**
     * @param Event $event
     */
    public function afterOrderSubmit(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);

        $order = $event->data['order'];

        $address = $order->getBillingAddress();
        if ($address && !$address->shop_customer_address_id) {
            if (!TableRegistry::get('Shop.ShopCustomerAddresses')->newRecordFromOrderAddress($order->shop_customer_id, $address)) {
                Log::error(sprintf('CustomerService::newRecordFromOrderAddress [B] failed for order %s', $order->id));
            }
        }

        $address = $order->getShippingAddress();
        if ($address && !$address->shop_customer_address_id) {
            if (!TableRegistry::get('Shop.ShopCustomerAddresses')->newRecordFromOrderAddress($order->shop_customer_id, $address)) {
                Log::error(sprintf('CustomerService::newRecordFromOrderAddress [S] failed for order %s', $order->id));
            }
        }
    }
}
