<?php

namespace Shop\Cron\Task;


use Cake\ORM\TableRegistry;
use Cron\Cron\CronTaskResult;
use Cron\Cron\Task\CronTask;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class ClearCreditcardDataCronTask
 *
 * This cron task ereases plain text credit card data in the shop_orders table,
 * created by the 'credit_card_internal' payment type
 *
 *
 * @package Shop\src\Cron
 * @property ShopOrdersTable $ShopOrders
 */
class ClearCreditcardDataCronTask extends CronTask
{

    public $limit = 5;

    /**
     * @return bool|CronTaskResult|null|mixed
     */
    public function execute()
    {
        $this->ShopOrders = TableRegistry::get('Shop.ShopOrders');

        // find orders with credit card data, older than 3 days
        $orders = $this->ShopOrders->find()
            ->contain([])
            ->where([
                'payment_type' => 'credit_card_internal',
                'payment_info_1 IS NOT' => 'DELETED',
                'created <=' => (new \DateTime())->setTimestamp(time() - 3 * DAY)
            ])
            ->order(['id' => 'ASC'])
            ->all();


        $processed = $failed = 0;
        foreach ($orders as $order) {
            $order->payment_info_1 = 'DELETED';
            $order->payment_info_2 = 'DELETED';
            $order->payment_info_3 = 'DELETED';

            if (!$this->ShopOrders->save($order, ['checkRules' => false])) {
                $failed++;
            }

            if (++$processed >= $this->limit) {
                break;
            }
        }

        //@TODO Send admin notification email

        return [true, sprintf("Found %d orders, %d processed, %d failed, %d successful",
            count($orders), $processed, $failed, $processed - $failed)];
    }
}