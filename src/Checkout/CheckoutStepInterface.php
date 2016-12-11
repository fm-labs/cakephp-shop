<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 12/10/16
 * Time: 10:14 PM
 */

namespace Shop\Checkout;


use Cake\Controller\Controller;
use Shop\Controller\Component\CheckoutComponent;

interface CheckoutStepInterface
{
    public function __construct(CheckoutComponent &$Checkout);

    public function getId();
    public function getUrl();

    public function execute(Controller $controller);
    public function isComplete();
}