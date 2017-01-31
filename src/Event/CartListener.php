<?php

namespace Shop\Event;


use Cake\Event\Event;
use Shop\Event\ShopEventListener;

class CartListener extends ShopEventListener
{
    public function implementedEvents()
    {
        return [
            'Shop.Cart.Item.beforeAdd' => 'beforeAdd',
            'Shop.Cart.Item.afterAdd' => 'afterAdd',
            'Shop.Cart.Item.beforeUpdate' => 'beforeUpdate',
            'Shop.Cart.Item.afterUpdate' => 'afterUpdate',
            'Shop.Cart.Item.beforeDelete' => 'beforeDelete',
            'Shop.Cart.Item.afterDelete' => 'afterDelete'
        ];
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