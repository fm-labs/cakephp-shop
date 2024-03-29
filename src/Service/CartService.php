<?php
declare(strict_types=1);

namespace Shop\Service;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Shop\Event\ShopEventLoggerTrait;

class CartService implements EventListenerInterface
{
    use ShopEventLoggerTrait;

    public function implementedEvents(): array
    {
        return [
            'Shop.Cart.beforeItemAdd' => 'beforeAdd',
            'Shop.Cart.afterItemAdd' => 'afterAdd',
            'Shop.Cart.beforeItemUpdate' => 'beforeUpdate',
            'Shop.Cart.afterItemUpdate' => 'afterUpdate',
            'Shop.Cart.beforeItemDelete' => 'beforeDelete',
            'Shop.Cart.afterItemDelete' => 'afterDelete',
//            'User.Auth.login' => 'onUserLogin',
//            'User.Auth.logout' => 'onUserLogout',
        ];
    }

//    public function onUserLogin(Event $event)
//    {
//        //@TODO restore user cart
//        $event->getSubject()->getController()->getRequest()->getSession()->delete('Shop.Customer');
//    }
//
//    public function onUserLogout(Event $event)
//    {
//        // save cart for user and delete from session
//        $event->getSubject()->getController()->getRequest()->getSession()->delete('Shop.Customer');
//        $this->_logEvent(__FUNCTION__, $event);
//    }

    public function beforeAdd(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    public function afterAdd(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    public function beforeUpdate(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    public function afterUpdate(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    public function beforeDelete(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    public function afterDelete(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }
}
