<?php

namespace Shop\Core\Payment\Engine;

use Cake\Core\Configure;
use Mpay24\Mpay24;
use Mpay24\MPay24Order;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Controller\Component\PaymentComponent;
use Shop\Core\Payment\PaymentEngineInterface;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderTransaction;

/**
 * Class Mpay24SofortPayment
 *
 * @package Shop\Core\Payment\Engine
 */
class Mpay24SofortPayment extends Mpay24SelectPayment
{
    /**
     * @param MPay24Order $mdxi
     * @return MPay24Order
     */
    protected function _buildPaymentMDXI(Mpay24Order $mdxi)
    {
        $mdxi->Order->PaymentTypes->setEnable("true");
        $mdxi->Order->PaymentTypes->Payment(1)->setType("SOFORT");

        return $mdxi;
    }
}
