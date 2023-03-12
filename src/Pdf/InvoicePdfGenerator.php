<?php

namespace Shop\Pdf;

use Cake\ORM\TableRegistry;

class InvoicePdfGenerator extends ShopPdfGenerator
{
    private \Cake\ORM\Table $ShopOrders;

    /**
     * Constructor
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->ShopOrders = TableRegistry::getTableLocator()->get('Shop.ShopOrders');
    }

//    /**
//     * @param null $orderId
//     * @param array $pdf
//     */
//    public function createFromOrder($orderId = null, array $pdf = [])
//    {
//        $shopOrder = $this->ShopOrders->get($orderId, [
//            'contain' => ['ShopCustomers', 'ShopOrderItems', 'BillingAddresses' => ['Countries'], 'ShippingAddresses' => ['Countries']],
//            'status' => true,
//        ]);
//
//        if (!Plugin::isLoaded('Tcpdf')) {
//            throw new MissingPluginException(['plugin' => 'Tcpdf']);
//        }
//
//        $view = new PdfView();
//        $view->setPlugin('Shop');
//        $view->setLayout('Shop.print');
//        $view->setTemplatePath('Admin/ShopOrders');
//        $view->setTemplate('Shop.printview');
//
//        $pdf = array_merge([
//            'title' => $shopOrder->title,
//            'subject' => $shopOrder->nr_formatted,
//            'keywords' => $shopOrder->nr_formatted,
//            'output' => 'F',
//            'filename' => null,
//        ], $pdf);
//        $viewVars = [
//            'pdfEngine' => static::$engineClass,
//            'pdf' => $pdf,
//            'shopOrder' => $shopOrder,
//            'mode' => 'invoice',
//        ];
//        $view->set($viewVars);
//        $view->render('printview');
//    }

    /**
     * @param null $orderId
     * @param array $pdf
     * @throws \Exception
     */
    public function generate(array $vars = [], array $pdf = [])
    {
        $orderId = $vars['orderId'] ?? null;
        $mode = $vars['mode'] ?? 'order';

        $shopOrder = $this->ShopOrders->get($orderId, [
            'contain' => ['ShopCustomers', 'ShopOrderItems', 'BillingAddresses' => ['Countries'], 'ShippingAddresses' => ['Countries']],
            'status' => true,
        ]);

        $this->setViewVars([
            'shopOrder' => $shopOrder,
            'mode' => $mode,
        ]);
        $this->render($pdf);
    }
}