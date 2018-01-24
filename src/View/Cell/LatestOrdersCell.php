<?php
namespace Shop\View\Cell;

use Cake\View\Cell;
use Shop\Model\Table\ShopOrdersTable;

/**
 * LatestOrdersCell cell
 *
 * @property ShopOrdersTable $ShopOrders
 */
class LatestOrdersCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display()
    {
        $this->loadModel('Shop.ShopOrders');

        $orders = $this->ShopOrders
            ->find('all', ['status' => true])
            ->where(['ShopOrders.is_temporary' => false])
            ->contain(['ShopCustomers'])
            ->limit(5)
            ->orderDesc('ShopOrders.id');

        $this->set('orders', $orders);
    }
}
