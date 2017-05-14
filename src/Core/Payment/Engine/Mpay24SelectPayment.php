<?php

namespace Shop\Core\Payment\Engine;


use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Routing\Router;
use Mpay24\Mpay24;
use Mpay24\Mpay24Config;
use Mpay24\MPay24Order;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Controller\Component\PaymentComponent;
use Shop\Core\Payment\PaymentEngineInterface;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderTransaction;

class Mpay24SelectPayment extends Mpay24BasePayment
{

    protected function _pay(Mpay24 $mpay24, MPay24Order $mdxi)
    {
        return $mpay24->paymentPage($mdxi);
    }
}