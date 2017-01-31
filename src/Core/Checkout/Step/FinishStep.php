<?php
namespace Shop\Core\Checkout\Step;


use Cake\Controller\Controller;
use Shop\Core\Checkout\CheckoutStepInterface;

class FinishStep extends BaseStep implements CheckoutStepInterface
{

    public function isComplete()
    {
        return false;
    }

    public function execute(Controller $controller)
    {
        if ($controller->request->is(['put', 'post'])) {
        }
        //$controller->Flash->success(__d('shop','The order has been successfully submitted'));
        //$controller->render('finish');
        $order = $this->Checkout->getOrder();
        $this->Checkout->Cart->reset();
        $controller->set('order', $order);
    }

}