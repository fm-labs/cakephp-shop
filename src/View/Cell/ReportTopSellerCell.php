<?php
namespace Shop\View\Cell;

use Cake\View\Cell;

/**
 * ReportTopSeller cell
 */
class ReportTopSellerCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = ['limit'];

    /**
     * @var int Number of top sellers to be displayed
     */
    public $limit = 10;

    /**
     * @var int Number of days back in order history
     */
    public $age = 90;

    /**
     * Default display method.
     *
     * @return void
     */
    public function display()
    {
    }

    public function products()
    {
        $orders = $this->loadModel('Shop.ShopOrders')->find('list')->where(['is_temporary' => false]);
        if ($this->age > 0) {
            $dateStart = new \DateTime();
            $dateStart->setTimestamp(time() - ($this->age * DAY));
            $orders->where(['submitted >=' => $dateStart]);
        }

        $orderItems = $this->loadModel('Shop.ShopOrderItems')->find('all')->where(['shop_order_id IN' => array_keys($orders->toArray())]);



        // sort & count
        $counter = [];
        $items = [];
        foreach ($orderItems as $orderItem) {
            //$key = sprintf("%s:%s", $orderItem->refscope, $orderItem->refid);
            $key = $orderItem->refid;
            if (!isset($counter[$key])) {
                $items[$key] = ['title' => $orderItem->title, 'sku' => $orderItem->sku];
                $counter[$key] = 0;
            }
            $counter[$key] += $orderItem->amount;
        }
        arsort($counter);

        // topseller
        $topsellers = [];
        $counter = array_slice($counter, 0, $this->limit, true);

        array_walk($counter, function($count, $key) use (&$topsellers, $items) {
            $item = $items[$key];
            $item['key'] = $key;
            $item['count'] = $count;

            $topsellers[] = $item;
        });
        $this->set('topsellers', $topsellers);
    }
}
