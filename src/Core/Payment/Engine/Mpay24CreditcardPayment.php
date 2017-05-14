<?php

namespace Shop\Core\Payment\Engine;


use Cake\Core\Configure;
use Mpay24\MPay24Order;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Controller\Component\PaymentComponent;
use Shop\Core\Payment\PaymentEngineInterface;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderTransaction;

class Mpay24CreditcardPayment extends Mpay24SelectPayment
{

    protected function _buildPaymentMDXI(Mpay24Order $mdxi)
    {
        $mdxi->Order->PaymentTypes->setEnable("true");
        $mdxi->Order->PaymentTypes->Payment(1)->setType("CC");

        return $mdxi;
    }

}