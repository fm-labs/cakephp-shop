<?php

namespace Shop\Event;

use Cake\Event\Event;

/**
 * Class CheckoutEvent
 *
 * @package Shop\Event
 */
class CartEvent extends Event
{
    /**
     * @return \Shop\Controller\Component\CartComponent
     */
    public function subject()
    {
        return parent::subject();
    }

    /**
     * @return \Shop\Controller\Component\CartComponent
     */
    public function getCart()
    {
        return $this->subject();
    }

    /**
     * @return \Shop\Model\Entity\ShopOrderItem
     */
    public function getItem()
    {
        return $this->data['item'];
    }

    /**
     * @return \Shop\Model\Entity\ShopCustomer
     */
    public function getCustomer()
    {
        return (isset($this->data['customer'])) ? $this->data['customer'] : null;
    }

    /**
     * @return array
     */
    public function getUserData()
    {
        return (isset($this->data['data'])) ? $this->data['data'] : [];
    }

    /**
     * @return \Shop\Core\Product\ShopProductInterface
     */
    public function getProductForCustomer()
    {
        return $this->getCart()->getProductForCustomer($this->data['item']['refid'], $this->data['item']['refscope']);
    }

    /**
     * @return \Shop\Core\Product\ShopProductInterface
     */
    public function getProduct()
    {
        return $this->getCart()->getProduct($this->data['item']['refid'], $this->data['item']['refscope']);
    }


}
