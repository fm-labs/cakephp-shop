<?php

namespace Shop\Lib;

use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;
use Cake\ORM\TableRegistry;
use Shop\Model\Table\ShopOrdersTable;
use Tcpdf\View\PdfView;

/**
 * Class InvoicePdfGenerator
 *
 * @package Shop\Lib
 */
class InvoicePdfGenerator
{
    /**
     * @var null|string
     */
    public static $engineClass = null;

    /**
     * @var ShopOrdersTable
     */
    public $ShopOrders;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ShopOrders = TableRegistry::getTableLocator()->get('Shop.ShopOrders');
    }

    /**
     * @param null $orderId
     * @param array $pdf
     */
    public function createFromOrder($orderId = null, $pdf = [])
    {
        $shopOrder = $this->ShopOrders->get($orderId, [
            'contain' => ['ShopCustomers', 'ShopOrderItems', 'BillingAddresses' => ['Countries'], 'ShippingAddresses' => ['Countries']],
            'status' => true
        ]);

        if (!Plugin::isLoaded('Tcpdf')) {
            throw new MissingPluginException(['plugin' => 'Tcpdf']);
        }

        $view = new PdfView();
        $view->plugin = 'Shop';
        $view->layoutPath(null);
        $view->templatePath('Admin/ShopOrders');
        $view->layout('Shop.print');
        $view->template('Shop.printview');

        $pdf = array_merge([
            'title' => $shopOrder->title,
            'subject' => $shopOrder->nr_formatted,
            'keywords' => $shopOrder->nr_formatted,
            'output' => 'F',
            'filename' => null
        ], $pdf);
        $viewVars = [
            'pdfEngine' => static::$engineClass,
            'pdf' => $pdf,
            'shopOrder' => $shopOrder,
            'mode' => 'invoice'
        ];
        $view->set($viewVars);
        $view->render('printview');
    }
}
