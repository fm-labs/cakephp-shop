<?php

namespace Shop\Lib;


use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;
use Cake\ORM\TableRegistry;
use Shop\Model\Table\ShopOrdersTable;
use Tcpdf\View\PdfView;

class InvoicePdfGenerator
{
    public static $engineClass = null;

    /**
     * @var ShopOrdersTable
     */
    public $ShopOrders;

    public function __construct()
    {
        $this->ShopOrders = TableRegistry::get('Shop.ShopOrders');
    }

    public function createPdf($id = null)
    {
        $shopOrder = $this->ShopOrders->get($id, [
            'contain' => ['ShopCustomers', 'ShopOrderItems', 'ShopOrderAddresses' => ['Countries']],
            'status' => true
        ]);

        if (!Plugin::loaded('Tcpdf')) {
            throw new MissingPluginException(['plugin' => 'Tcpdf']);
        }

        $view = new PdfView();
        $view->plugin = 'Shop';
        $view->layoutPath(null);
        $view->templatePath('Admin/ShopOrders');
        $view->layout('Shop.print');
        $view->template('Shop.printview');

        $viewVars = [
            'pdfEngine' => static::$engineClass,
            'pdf' => [
                'title' => $shopOrder->title,
                'subject' => $shopOrder->nr_formatted,
                'keywords' => $shopOrder->nr_formatted,
                'output' => 'F'
            ],
            'shopOrder' => $shopOrder
        ];
        $view->set($viewVars);
        $view->render('printview');
    }
}