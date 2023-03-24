<?php

namespace Shop\Core\Order;

use Cake\ORM\Locator\TableLocator;
use Cake\ORM\TableRegistry;

class OrderManager
{
    public function getTable(): OrderTableInterface
    {
        /** @var \Shop\Core\Order\OrderTableInterface $table */
        $table = TableRegistry::getTableLocator()
            ->get('Shop.ShopOrders');
        return $table;
    }

    /**
     * @param \Shop\Core\Order\OrderInterface $order
     * @return \Shop\Core\Order\OrderInterface
     * @throws \Exception
     */
    public function saveOrder(OrderInterface $order)
    {
        return $this->getTable()->saveOrder($order);
    }

    /**
     * @param \Shop\Core\Order\OrderInterface $order
     * @return \Shop\Core\Order\OrderInterface
     * @throws \Exception
     */
    public function submitOrder(OrderInterface $order)
    {
        return $this->getTable()->submitOrder($order);
    }

    /**
     * @param \Shop\Core\Order\OrderInterface $order
     * @return \Shop\Core\Order\OrderInterface
     * @throws \Exception
     */
    public function cancelOrder(OrderInterface $order)
    {
        return $this->getTable()->cancelOrder($order);
    }
}