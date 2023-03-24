<?php

namespace Shop\Core\Order;

use Shop\Core\Address\AddressInterface;

interface OrderTableInterface
{
    /**
     * @param \Shop\Core\Order\OrderInterface $order
     * @return \Shop\Core\Order\OrderInterface
     * @throws \Exception
     */
    public function saveOrder(OrderInterface $order); // @todo

    /**
     * @param \Shop\Core\Order\OrderInterface $order
     * @param array $data
     * @return \Shop\Core\Order\OrderInterface
     * @throws \Exception
     */
    public function submitOrder(OrderInterface $order, array $data = []): OrderInterface;

    /**
     * @param \Shop\Core\Order\OrderInterface $order
     * @return \Shop\Core\Order\OrderInterface
     * @throws \Exception
     */
    public function cancelOrder(OrderInterface $order): OrderInterface;

    /**
     * @param \Shop\Core\Order\OrderInterface $order
     * @return \Shop\Core\Order\OrderInterface
     * @throws \Exception
     */
    public function confirmOrder(OrderInterface $order): OrderInterface;

    /**
     * @param \Shop\Core\Order\OrderInterface $order
     * @param \Shop\Core\Address\AddressInterface $address
     * @return \Shop\Core\Order\OrderInterface
     * @throws \Exception
     */
    public function setBillingAddress(OrderInterface $order, AddressInterface $address): OrderInterface;

    /**
     * @param \Shop\Core\Order\OrderInterface $order
     * @param \Shop\Core\Address\AddressInterface $address
     * @return \Shop\Core\Order\OrderInterface
     * @throws \Exception
     */
    public function setShippingAddress(OrderInterface $order, AddressInterface $address): OrderInterface;
}
