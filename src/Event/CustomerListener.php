<?php

namespace Shop\Event;


use Cake\Event\Event;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Shop\Event\ShopEventListener;

class CustomerListener extends ShopEventListener
{
    public function implementedEvents()
    {
        return [
            'User.login' => 'onUserLogin',
            'User.logout' => 'onUserLogout',
            //'Auth.identifyUser' => 'onUserLogin', // <-- Hmm, can't capture this event...
            //'Auth.logout' => 'onUserLogout',
        ];
    }

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

    public function onUserLogout(Event $event)
    {
        $event->subject()->request->session()->delete('Shop.Customer');
        $event->subject()->request->session()->delete('Shop.Order');
        $event->subject()->request->session()->delete('Shop.Cart');
        $event->subject()->request->session()->delete('Shop.Checkout');
    }

}