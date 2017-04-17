<?php

namespace Shop\Event;


use Cake\Event\Event;
use Shop\Event\ShopEventListener;

class CartListener extends ShopEventListener
{
    public function implementedEvents()
    {
        return [
            'Shop.Cart.beforeItemAdd' => 'beforeAdd',
            'Shop.Cart.afterItemAdd' => 'afterAdd',
            'Shop.Cart.beforeItemUpdate' => 'beforeUpdate',
            'Shop.Cart.afterItemUpdate' => 'afterUpdate',
            'Shop.Cart.beforeItemDelete' => 'beforeDelete',
            'Shop.Cart.afterItemDelete' => 'afterDelete',
            'User.login' => 'onUserLogin',
            'User.logout' => 'onUserLogout'
        ];
    }

    public function onUserLogin(Event $event)
    {
        //@TODO restore user cart
        $event->subject()->request->session()->delete('Shop.Customer');
    }

    public function onUserLogout(Event $event)
    {
        // save cart for user and delete from session
        $event->subject()->request->session()->delete('Shop.Customer');
        $this->_logEvent(__FUNCTION__, $event);
    }

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