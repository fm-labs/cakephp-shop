<?php
declare(strict_types=1);

namespace Shop\Core\Checkout\Step;

use Cake\Controller\Controller;
use Shop\Core\Checkout\CheckoutStepInterface;

/**
 * Class SubmitStep
 *
 * @package Shop\Core\Checkout\Step
 */
class SubmitStep extends BaseStep implements CheckoutStepInterface
{
    /**
     * @return null|string
     */
    public function getTitle()
    {
        return __d('shop', 'Review');
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        return $this->Checkout->getOrder()->is_temporary ? false : true;
    }

    /**
     * @param \Cake\Controller\Controller $controller
     * @return \Cake\Http\Response|null
     */
    public function execute(Controller $controller)
    {
        if ($controller->getRequest()->is(['put', 'post'])) {
            $order = $this->Checkout->submitOrder($controller->getRequest()->getData());
            if ($order && empty($order->getErrors()) && $order->is_temporary == false) {
                $controller->Flash->success(__d('shop', 'Order has been submitted'));

                return $controller->redirect(['plugin' => 'Shop', 'controller' => 'Orders', 'action' => 'process', $order->uuid]);
            } else {
                debug($this->Checkout->getOrder()->getErrors());
                $controller->Flash->error(__d('shop', 'Please fill all required fields'));
                //$this->Checkout->redirectNext();
            }
        }

        return $controller->render('submit');
    }
}
