<?php

namespace Shop\Controller;

class CustomerController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->deny([]);
    }

    public function index()
    {
        //$this->autoRender = false;
        $customer = $this->request->session()->read('Shop.Customer');
        $this->set(compact('customer'));
    }

}