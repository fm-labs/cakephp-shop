<?php
declare(strict_types=1);

namespace Shop\Controller;

class CustomerController extends AppController
{
    public function index()
    {
        //$this->autoRender = false;
        $customer = $this->request->getSession()->read('Shop.Customer');
        $this->set(compact('customer'));
    }
}
