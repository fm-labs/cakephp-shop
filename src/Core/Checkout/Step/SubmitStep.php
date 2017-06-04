<?php
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
        return __d('shop','Review');
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        return ($this->Checkout->getOrder()->is_temporary) ? false : true;
    }

    /**
     * @param Controller $controller
     * @return \Cake\Network\Response|null
     */
    public function execute(Controller $controller)
    {
        if ($controller->request->is(['put', 'post'])) {
            if (($order = $this->Checkout->submitOrder($controller->request->data)) && $order->is_temporary == false) {
                $controller->Flash->success(__d('shop','Order has been submitted'));
                return $controller->redirect(['plugin' => 'Shop', 'controller' => 'Orders', 'action' => 'process', $order->uuid]);
            } else {
                debug($this->Checkout->getOrder()->errors());
                $controller->Flash->error(__d('shop','Please fill all required fields'));
                //$this->Checkout->redirectNext();
            }
        }

        return $controller->render('submit');
    }
}
