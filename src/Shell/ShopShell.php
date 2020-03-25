<?php
declare(strict_types=1);

namespace Shop\Shell;

use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;

/**
 * Class ShopShell
 *
 * @package Shop\Shell
 * @property \Shop\Shell\Task\ProductImportTask $ProductImport
 * @property \Shop\Shell\Task\CustomerIntegrityCheckTask $CustomerIntegrityCheck
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 * @property \Shop\Model\Table\ShopProductsTable $ShopProducts
 */
class ShopShell extends Shell
{
    /**
     * @var array
     */
    public $tasks = [
        'Shop.ProductImport',
        'Shop.CustomerIntegrityCheck',
    ];

    /**
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser(): ConsoleOptionParser
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('product_import', [
            'help' => 'Import shop products from CSV file',
            'parser' => $this->ProductImport->getOptionParser(),
        ]);
        $parser->addSubcommand('customer_integrity_check', [
            'help' => 'Check customer data integrity',
            'parser' => $this->CustomerIntegrityCheck->getOptionParser(),
        ]);
        $parser->addSubcommand('clean_temp_orders', [
            'help' => 'Execute cleanTempOrders',
        ]);
        $parser->addSubcommand('patch_product_price', [
            'help' => 'Execute patchProductPrice',
        ]);
        $parser->addSubcommand('patch_order_numbers', [
            'help' => 'Execute patchOrderNumbers',
        ]);
        $parser->addSubcommand('patch_order_customer_email', [
            'help' => 'Execute patchOrderCustomerEmail',
        ]);

        return $parser;
    }

    /**
     * Cleanup temporary orders
     */
    public function cleanTempOrders()
    {
        $this->loadModel('Shop.ShopOrders');
        $orderList = $this->ShopOrders->find('list')->where(['is_temporary' => true])->all();

        foreach ($orderList as $orderId => $orderTitle) {
            //continue;

            $itemsCount = $this->ShopOrders->ShopOrderItems->find()->where(['shop_order_id' => $orderId])->count();
            $this->out("Processing order #$orderId with $itemsCount items");

            if ($itemsCount > 0 && !$this->ShopOrders->ShopOrderItems->deleteAll(['shop_order_id' => $orderId])) {
                $this->err("Failed to delete order items of order #" . $orderId);
                continue;
            }

            if (!$this->ShopOrders->deleteAll(['id' => $orderId])) {
                $this->err("Failed to delete order #" . $orderId);
                continue;
            }
        }
    }

    /**
     * Patch product prices
     * Parse price as gros-price and patch to net-price
     *
     * @deprecated
     */
    public function patchProductPrice()
    {
        $this->loadModel('Shop.ShopProducts');

        $products = $this->ShopProducts->find()->contain([])->all();

        $failed = 0;
        foreach ($products as $product) {
            $this->out("Processing product #$product->id");

            if ($product->price <= 0) {
                continue;
            }

            $taxRate = 20.00;
            $price = $product->price;

            if (preg_match('/^Gutschein/i', $product->title)) {
                $this->out("Gutschein!");
                $taxRate = 0;
            }

            // calculate patch values
            $taxRatio = 1 + ($taxRate / 100);
            $priceNet = round($product->price / $taxRatio, 3);
            $taxed = round($priceNet * $taxRatio, 3);

            $this->out("Patching price $price -> $priceNet = $taxed");

            $product->price_net = $priceNet;
            $product->tax_rate = $taxRate;

            if (!$this->ShopProducts->save($product)) {
                $this->err("Failed to patch product #" . $product->id);
                $failed++;
            }
        }

        $this->out("Failed: $failed");
    }

    /**
     * Patch order numbers
     * Assign order numbers to non-temporary orders
     */
    public function patchOrderNumbers()
    {
        $this->out('<info>Patching order numbers</info>');

        $this->loadModel('Shop.ShopOrders');
        $orders = $this->ShopOrders->find()
            ->contain([])
            ->where(['ShopOrders.is_temporary' => false /*, 'ShopOrders.nr IS NULL' */])
            ->order(['ShopOrders.submitted' => 'ASC'])
            ->all();

        foreach ($orders as $order) {
            //$this->out(sprintf("Patching order [ID:%s] #%s", $order->id, $order->nr_formatted));
            if ($order->nr) {
                continue;
            }

            $submitted = $order->submitted;
            $year = $submitted->format("Y");

            $nr = $this->ShopOrders->getNextOrderNr($year);
            $order->ordergroup = $year;
            $order->nr = $nr;
            $out = "Next number for order with id " . $year . "|" . $order->id . " -> " . $nr;

            if ($this->ShopOrders->save($order)) {
                //$this->out($out . ' Patched!');
            } else {
                $this->abort($out . ' Failed!');
            }
        }
    }

    /**
     * Patch order's customer_email data field from customer's email info
     */
    public function patchOrderCustomerEmail()
    {
        $this->out('<info>Patching order customer_email from shop-customers email</info>');

        $this->loadModel('Shop.ShopOrders');
        $orders = $this->ShopOrders->find()
            ->contain(['ShopCustomers'])
            ->where(['ShopOrders.customer_email IS NULL'])
            ->all();

        $patched = $failed = 0;
        foreach ($orders as $order) {
            if (!$order->shop_customer || $order->customer_email) {
                continue;
            }

            $order->customer_email = $order->shop_customer->email;

            $out = "Customer email for order with id " . $order->id . " -> " . $order->customer_email;
            if ($this->ShopOrders->save($order)) {
                //$this->out($out . ': Patched!');
                $patched++;
            } else {
                $this->abort($out . ': Failed!');
                $failed++;
            }
        }

        $this->out("<info>Patched: $patched - Failed: $failed</info>");
    }
}
