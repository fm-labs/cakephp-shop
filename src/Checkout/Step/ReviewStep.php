<?php
namespace Shop\Checkout\Step;


use Cake\Controller\Controller;
use Shop\Checkout\CheckoutStepInterface;

class ReviewStep extends BaseStep implements CheckoutStepInterface
{

    public function isComplete()
    {
        return false;
    }

    public function execute(Controller $controller)
    {
        if ($controller->request->is(['put', 'post'])) {
            if ($this->Checkout->submitOrder($controller->request->data)) {
                $controller->Flash->success(__d('shop','Order has been submitted'));
            } else {
                $controller->Flash->error(__d('shop','Ups. Something went wrong. Please try again.'));
            }
        }
        $controller->render('review');
    }

}