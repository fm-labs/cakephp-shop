<?php
declare(strict_types=1);

namespace Shop\Core\Payment\Engine;

use Mpay24\MPay24Order;

/**
 * Class Mpay24SofortPayment
 *
 * @package Shop\Core\Payment\Engine
 */
class Mpay24SofortPayment extends Mpay24SelectPayment
{
    /**
     * @param \Mpay24\MPay24Order $mdxi
     * @return \Mpay24\MPay24Order
     */
    protected function _buildPaymentMDXI(Mpay24Order $mdxi)
    {
        $mdxi->Order->PaymentTypes->setEnable("true");
        $mdxi->Order->PaymentTypes->Payment(1)->setType("SOFORT");

        return $mdxi;
    }
}
