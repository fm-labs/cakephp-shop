<?php
declare(strict_types=1);

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
    public function getSubject()
    {
        return parent::getSubject();
    }

    /**
     * @return \Shop\Controller\Component\CartComponent
     */
    public function getCart()
    {
        return $this->getSubject();
    }

    /**
     * @return \Shop\Model\Entity\ShopOrderItem
     */
    public function getItem()
    {
        return $this->getData()['item'];
    }

    /**
     * @return \Shop\Model\Entity\ShopCustomer
     */
    public function getCustomer()
    {
        return $this->getData('customer');
    }

    /**
     * @return array
     */
    public function getUserData()
    {
        return $this->getData()['data'] ?? [];
    }

    /**
     * @return \Shop\Core\Product\ShopProductInterface
     */
    public function getProductForCustomer()
    {
        return $this->getCart()->getProductForCustomer(
            $this->getData()['item']['refid'],
            $this->getData()['item']['refscope']
        );
    }

    /**
     * @return \Shop\Core\Product\ShopProductInterface
     */
    public function getProduct()
    {
        return $this->getCart()->getProduct(
            $this->getData()['item']['refid'],
            $this->getData()['item']['refscope']
        );
    }
}
