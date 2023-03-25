<?php
declare(strict_types=1);

namespace Shop\Service;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Shop\Event\ShopEventLoggerTrait;

/**
 * Class CustomerService
 *
 * @package Shop\Event
 */
class CustomerService implements EventListenerInterface
{
    use ShopEventLoggerTrait;

    /**
     * @return array
     */
    public function implementedEvents(): array
    {
        return [
            'User.Auth.login' => 'onUserLogin',
            'User.Auth.logout' => 'onUserLogout',


            'User.Model.User.register' => 'onUserRegister',
            'Shop.Model.Order.afterSubmit' => 'afterOrderSubmit',
            //'Auth.identifyUser' => 'onUserLogin', // <-- Hmm, can't capture this event...
            //'Auth.logout' => 'onUserLogout',
        ];
    }

    /**
     * @param \Cake\Event\Event $event
     */
    public function onUserRegister(Event $event)
    {
        $user = $event->getData('user');
        $customer = null;

        try {
            $customer = TableRegistry::getTableLocator()->get('Shop.ShopCustomers')->createFromUser($user, $event->getData('data'));
        } catch (\Exception $ex) {
            Log::error('CustomerEventListener::onUserRegister: ' . $ex->getMessage());
        }

        if ($customer) {
            Log::debug('[shop] Set customer for user ' . $user->id);
            //$event->getSubject()->getController()->getRequest()->getSession()->write('Shop.Customer', $customer->toArray());
        } else {
            Log::alert('[shop] Failed to create customer for user ' . $user->id);
            //$event->getSubject()->getController()->getRequest()->getSession()->delete('Shop.Customer');
        }
    }

    /**
     * @param \Cake\Event\Event $event
     */
    public function onUserLogin(Event $event)
    {
        // user login detected
        $userId = $event->getData('user')['id'];
        if (!$userId) {
            Log::alert('[shop] Login without userId detected');

            return;
        }

        $customer = TableRegistry::getTableLocator()->get('Shop.ShopCustomers')->find()
            ->where(['ShopCustomers.user_id' => $userId])
            ->contain([])
            ->first();

        // attempt to auto-create customer for user
        if (!$customer) {
            try {
                $customer = TableRegistry::getTableLocator()->get('Shop.ShopCustomers')->createFromUserId($userId);
            } catch (\Exception $ex) {
                Log::error('CustomerEventListener::onUserLogin: ' . $ex->getMessage());
            }
        }

        if ($customer) {
            Log::debug('[shop] Set customer for user ' . $userId);
            $event->getSubject()->getController()->getRequest()->getSession()->write('Shop.Customer', $customer->toArray());
        } else {
            Log::alert('[shop] Failed to create customer for user ' . $userId);
            $event->getSubject()->getController()->getRequest()->getSession()->delete('Shop.Customer');
        }
    }

    /**
     * @param \Cake\Event\Event $event
     */
    public function onUserLogout(Event $event)
    {
        $event->getSubject()->getController()->getRequest()->getSession()->delete('Shop.Customer');
        $event->getSubject()->getController()->getRequest()->getSession()->delete('Shop.Order');
        $event->getSubject()->getController()->getRequest()->getSession()->delete('Shop.Cart');
        $event->getSubject()->getController()->getRequest()->getSession()->delete('Shop.Checkout');
    }

    /**
     * @param \Cake\Event\Event $event
     */
    public function afterOrderSubmit(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);

        $order = $event->getData('order');

        $address = $order->getBillingAddress();
        if ($address && !$address->shop_customer_address_id) {
            if (!TableRegistry::getTableLocator()->get('Shop.ShopCustomerAddresses')->newRecordFromOrderAddress($order->shop_customer_id, $address)) {
                Log::error(sprintf('CustomerService::newRecordFromOrderAddress [B] failed for order %s', $order->id));
            }
        }

        $address = $order->getShippingAddress();
        if ($address && !$address->shop_customer_address_id) {
            if (!TableRegistry::getTableLocator()->get('Shop.ShopCustomerAddresses')->newRecordFromOrderAddress($order->shop_customer_id, $address)) {
                Log::error(sprintf('CustomerService::newRecordFromOrderAddress [S] failed for order %s', $order->id));
            }
        }
    }
}
