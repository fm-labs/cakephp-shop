<?php

namespace Shop\Core\Shipping;

use Cake\Network\Response;
use Shop\Controller\Component\CheckoutComponent;

/**
 * Interface ShippingEngineInterface
 *
 * @package Shop\Core\Shipping
 */
interface ShippingEngineInterface
{
    /**
     * @param CheckoutComponent $Checkout
     * @return bool
     */
    public function isCheckoutComplete(CheckoutComponent $Checkout);

    /**
     * @param CheckoutComponent $Checkout
     * @return null|Response
     */
    public function checkout(CheckoutComponent $Checkout);
}
