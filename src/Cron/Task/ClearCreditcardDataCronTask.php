<?php
declare(strict_types=1);

namespace Shop\Cron\Task;

use Cake\ORM\TableRegistry;
use Cron\Cron\BaseCronTask;

/**
 * Class ClearCreditcardDataCronTask
 *
 * This cron task ereases plain text credit card data in the shop_orders table,
 * created by the 'credit_card_internal' payment type
 *
 *
 * @package Shop\src\Cron
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 */
class ClearCreditcardDataCronTask extends BaseCronTask
{
    /**
     * @var int Max number of orders processed per task execution
     */
    public $limit = 10;

    /**
     * @var int Number of days to keep information stored in db
     */
    public $daysKeep = 4;

    /**
     * @return bool|\Cron\Cron\CronTaskResult|null|mixed
     */
    public function execute()
    {
        $this->ShopOrders = TableRegistry::getTableLocator()->get('Shop.ShopOrders');

        // find orders with credit card data, older than 3 days
        $orders = $this->ShopOrders->find()
            ->contain([])
            ->where([
                'payment_type' => 'credit_card_internal',
                'payment_info_1 IS NOT' => 'DELETED',
                'created <=' => (new \DateTime())->setTimestamp(time() - $this->daysKeep * DAY),
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

        return [true, sprintf(
            "Found %d orders, %d processed, %d failed, %d successful",
            count($orders),
            $processed,
            $failed,
            $processed - $failed
        )];
    }
}
