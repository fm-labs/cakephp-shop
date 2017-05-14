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

class Mpay24SofortPayment extends Mpay24SelectPayment
{

    protected function _buildPaymentMDXI(Mpay24Order $mdxi)
    {
        $mdxi->Order->PaymentTypes->setEnable("true");
        $mdxi->Order->PaymentTypes->Payment(1)->setType("CC");

        return $mdxi;
    }

    protected function _pay(Mpay24 $mpay24, MPay24Order $mdxi)
    {
        return $mpay24->paymentPage($mdxi);
    }
}