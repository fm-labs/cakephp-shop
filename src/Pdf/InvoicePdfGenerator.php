<?php

namespace Shop\Pdf;

use Cake\ORM\TableRegistry;

/**
 * InvoicePdfGenerator
 */
class InvoicePdfGenerator extends ShopPdfGenerator
{
    private \Shop\Model\Table\ShopOrdersTable $ShopOrders;

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
     * @param array $vars
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
        $calculator = $this->ShopOrders->getOrderCalculator($shopOrder);

        $title = $mode == "order" ? $shopOrder->nr_formatted : $shopOrder->invoice_nr_formatted;
        $pdf['title'] = $pdf['title'] ?? $title;
        //$pdf['subject'] = $pdf['subject'] ?? $title;
        //$pdf['keywords'] = $pdf['keywords'] ?? $title;


        $this->setViewVars([
            'shopOrder' => $shopOrder,
            'calculator' => $calculator,
            'mode' => $mode,
        ]);
        $this->view->loadHelper('Bootstrap.Bootstrap');
        //$this->view->Html->css();
        $this->render($pdf);
    }
}