<?php
declare(strict_types=1);

namespace Shop\Core\Shipping;

use Shop\Controller\Component\CheckoutComponent;

/**
 * Interface ShippingEngineInterface
 *
 * @package Shop\Core\Shipping
 */
interface ShippingEngineInterface
{
    /**
     * @param \Shop\Controller\Component\CheckoutComponent $Checkout
     * @return bool
     */
    public function isCheckoutComplete(CheckoutComponent $Checkout);

    /**
     * @param \Shop\Controller\Component\CheckoutComponent $Checkout
     * @return null|\Cake\Http\Response
     */
    public function checkout(CheckoutComponent $Checkout);
}
