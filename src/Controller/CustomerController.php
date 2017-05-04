<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 1/15/17
 * Time: 6:29 PM
 */

namespace Shop\Controller;


use Attachment\Controller\AppController;

class CustomerController extends AppController
{
    public function index()
    {
        //$this->autoRender = false;
        $customer = $this->request->session()->read('Shop.Customer');
        $this->set(compact('customer'));
    }

}