<?php
namespace Shop\Core\Checkout\Step;


use Cake\Controller\Controller;
use Shop\Core\Checkout\CheckoutStepInterface;

class ReviewStep extends BaseStep implements CheckoutStepInterface
{

    public function isComplete()
    {
        return ($this->Checkout->getOrder()->is_temporary) ? false : true;
    }

    public function execute(Controller $controller)
    {
        if ($controller->request->is(['put', 'post'])) {
            if ($this->Checkout->submitOrder($controller->request->data)) {
                $controller->Flash->success(__d('shop','Order has been submitted'));
                $this->Checkout->redirectNext();
            } else {
                $controller->Flash->error(__d('shop','Please fill all required fields'));
            }
        }
        $controller->render('review');
    }

}