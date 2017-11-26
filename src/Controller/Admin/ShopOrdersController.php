<?php
namespace Shop\Controller\Admin;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\View\View;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Table\ShopOrdersTable;

/**
 * ShopOrders Controller
 *
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 * @property \Backend\Controller\Component\ActionComponent $Action
 */
class ShopOrdersController extends AppController
{
    /**
     * @var string
     */
    public $modelClass = "Shop.ShopOrders";

    /**
     * @var array
     */
    public $actions = [
        'index'     => 'Backend.Index',
        'view'      => 'Backend.View',
    ];

    /**
     * Initialize method
     */
    public function initialize()
    {
        parent::initialize();
        //$this->loadComponent('RequestHandler');

        $this->Action->registerInline('storno', ['label' => __d('shop', 'Cancel order'), 'scope' => ['form', 'table'], 'attrs' => ['data-icon' => 'trash']]);
        //$this->Action->registerInline('printview', ['label' => __d('shop', 'Print view'), 'scope' => ['form', 'table'], 'attrs' => ['data-icon' => 'print']]);
        //$this->Action->registerInline('orderpdf', ['label' => __d('shop', 'Order PDF'), 'scope' => ['table'], 'attrs' => ['data-icon' => 'file-pdf-o']]);
        //$this->Action->registerInline('invoicepdf', ['label' => __d('shop', 'Invoice PDF'), 'scope' => ['table'], 'attrs' => ['data-icon' => 'file-pdf-o']]);
        //$this->Action->registerInline('invoicepdf', ['label' => __d('shop', 'Send order confirmation'), 'scope' => ['form', 'table'], 'attrs' => ['data-icon' => 'file-pdf-o']]);
    }

    public function storno($id = null)
    {
        $order = $this->ShopOrders->get($id, ['contain' => []]);

        if ($this->request->is('post')) {
            $order = $this->ShopOrders->patchEntity($order, [
                'status' => ShopOrdersTable::ORDER_STATUS_STORNO,
                'invoice_nr' => null
            ]);
            if ($this->ShopOrders->save($order)) {
                $this->Flash->success(__d('shop', 'Updated'));
                return $this->redirect(['action' => 'index']);

            } else {
                $this->Flash->error(__d('shop', 'Operation failed'));
                debug($order->errors());
            }
        }

        $this->set('order', $order);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ShopCustomers'/*, 'ShopOrderAddresses' => ['Countries'], 'BillingAddresses' => ['Countries'], 'ShippingAddresses' => ['Countries']*/],
            'conditions' => ['ShopOrders.is_temporary' => false],
            'order' => ['ShopOrders.id' => 'DESC'],
            'status' => true,
        ];

        $this->set('paginate', true);
        $this->set('sortable', true);
        $this->set('ajax', true);

        $this->set('fields', [
            'submitted',
            'nr_formatted' => ['label' => __d('shop', 'Order Nr')],
            'invoice_nr_formatted'  => ['label' => __d('shop', 'Invoice Nr')],
            'shop_customer' => ['formatter' => ['related', 'display_name'], 'type' => 'object'],
            'order_value_total' => ['label' => 'Total Value', 'formatter' => 'currency' , 'class' => 'text-right'],
            'status__status' => ['label' => 'Status', 'formatter' => 'status', 'type' => 'object']
        ]);

        $this->Action->execute();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Order id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shopOrder = $this->ShopOrders->get($id, [
            'contain' => ['ShopCustomers' => ['Users'], 'ShopOrderItems', 'BillingAddresses' => ['Countries'], 'ShippingAddresses' => ['Countries'], 'ShopOrderTransactions', 'ShopOrderAddresses', 'ShopOrderNotifications'],
            'status' => true
        ]);
        $this->set('entity', $shopOrder);
        $this->set('_serialize', 'entity');

        $this->set([
            'title' => $shopOrder->get('nr_formatted'),
            'model' => 'Shop.ShopOrders',
            'fields.whitelist' => true,
            'fields' => [
                '_status' => ['formatter' => function($val, $row, $args, View $view) {
                    $view->loadHelper('Banana.Status');
                    return $view->Status->label($val);
                }],
                'shop_customer_id' => ['formatter' => function($val, $row, $args, $view) {
                    return ($row->shop_customer) ? $view->Html->link($row->shop_customer->displayName, ['controller' => 'ShopCustomers', 'action' => 'view', $row->shop_customer->id]) : null;
                }],
                'submitted' => [],
                'nr_formatted' => ['formatter' => function($val, $row, $args, $view) {
                    return $view->Html->link($val, ['action' => 'view', $row->id]);
                }],
                'ordergroup' => [],
                'title' => ['formatter' => function() {}],
                'items_value_taxed' => [],
                'order_value_total' => [],
                'shipping_type' => [],
                'payment_type' => [],
                'payment_info_1' => [],
                'payment_info_2' => [],
                'payment_info_3' => [],
                'customer_phone' => [],
                'customer_mail' => [],
                'is_temporary' => [],
                'is_storno' => [],
                'is_deleted' => [],
                'shop_order_transactions' => [],
                'shop_customers' => [],
                'shop_order_items' => [],
                'shop_order_addresses' => [],
                'billing_addresses' => [],
                'shipping_addresses' => []
            ],
        ]);

        $this->set('related', [
            //'ShopCustomers',
            'ShopOrderItems' => [
                'fields' => [
                    'id', 'title', 'amount', 'item_value_net', 'tax_rate', 'value_net', 'value_tax', 'value_total', 'is_processed', 'last_message'
                ]
            ],
            'ShopOrderTransactions' => [
                'fields' => [
                    'id', 'type', 'engine', 'currency_code', 'value', 'status', 'ext_txnid', 'ext_status', 'last_message', 'is_test'
                ]
            ],
            'ShopOrderNotifications' => [

            ],
            'ShopOrderAddresses' => [
                'fields' => [
                    'id', 'type', 'is_company', 'company_name', 'first_name', 'last_name', 'street', 'zipcode', 'city'
                ]
            ],
            'BillingAddresses' => [
                'fields' => [
                    'id', 'is_company', 'company_name', 'first_name', 'last_name', 'street', 'zipcode', 'city'
                ]
            ],
            'ShippingAddresses' => [
                'fields' => [
                    'id', 'is_company', 'company_name', 'first_name', 'last_name', 'street', 'zipcode', 'city'
                ]
            ]
        ]);

        /*
        $this->set('tabs', [
            'order-items' => [
                'title' => __d('shop', 'Order Items'),
                'url' => ['controller' => 'ShopOrderItems', 'action' => 'index', 'order_id' => $shopOrder->id]
            ],
            'order-transactions' => [
                'title' => __d('shop', 'Transactions'),
                'url' => ['controller' => 'ShopOrderTransactions', 'action' => 'index', 'shop_order_id' => $shopOrder->id]
            ],
            //'raw' => [
            //    'title' => __d('shop', 'Raw Data'),
            //    'url' => ['plugin' => 'Backend', 'controller' => 'Entity', 'action' => 'view', 'Shop.ShopOrders', $shopOrder->id]
            //]
        ]);
        */

        $this->set('template', 'view');

        //$this->noActionTemplate = true;
        $this->Action->execute();
    }

    /**
     * @param null $id
     * @param null $mode
     */
    public function printview($id = null, $mode = null)
    {
        $mode = ($mode) ?: $this->request->query('mode');

        $shopOrder = $this->ShopOrders->get($id, [
            'contain' => ['ShopCustomers' => ['Users'], 'ShopOrderItems', 'BillingAddresses' => ['Countries'], 'ShippingAddresses' => ['Countries']],
            'status' => true
        ]);
        $this->set('shopOrder', $shopOrder);
        $this->set('mode', $mode);

        $this->viewBuilder()->layout('Shop.print');
        $this->render('printview');
    }

    /**
     * @param null $id
     * @param null $mode
     */
    public function pdfview($id = null, $mode = null)
    {
        $mode = ($mode) ?: $this->request->query('mode');

        $shopOrder = $this->ShopOrders->get($id, [
            'contain' => ['ShopCustomers' => ['Users'], 'ShopOrderItems', 'BillingAddresses' => ['Countries'], 'ShippingAddresses' => ['Countries']],
            'status' => true
        ]);
        $this->set('shopOrder', $shopOrder);

        $this->viewBuilder()->className('Tcpdf.Pdf');
        $this->viewBuilder()->layout('Shop.print');

        $this->set('pdfEngine', Configure::read('Shop.Pdf.engine'));
        $this->set('pdf', [
            'title' => $shopOrder->title,
            'subject' => $shopOrder->nr_formatted,
            'keywords' => $shopOrder->nr_formatted,
            //'output' => 'browser'
        ]);
        $this->set('mode', $mode);

        $this->render('printview');
    }

    /**
     * View method
     *
     * @param string|null $id Shop Order id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     * //@TODO Implement emailOwnerOrderNotify action
     */
    public function emailOwnerOrderNotify($id = null)
    {
        if ($debug = $this->ShopOrders->emailOwnerOrderNotify($id)) {
            $this->Flash->success(__d('shop', 'The notification has been sent.'));
        } else {
            $this->Flash->error(__d('shop', 'The notification could not be sent.'));
        };

        if (Configure::read('debug')) {
            $this->autoRender = false;
        } else {
            $this->redirect($this->referer(['action' => 'index']));
        }
    }

    /**
     * View method
     *
     * @param string|null $id Shop Order id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function calculate($id = null)
    {
        if ($debug = $this->ShopOrders->calculate($id)) {
            $this->Flash->success('OK');
        } else {
            $this->Flash->error('FAILED');
        };
        //$this->autoRender = false;
        $this->redirect($this->referer(['action' => 'edit', $id]));
    }

    /**
     * View method
     *
     * @param string|null $id Shop Order id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function costs($id = null)
    {
        $shopOrder = $this->ShopOrders->get($id, [
            'contain' => ['ShopCustomers' => ['Users'], 'ShopOrderItems', 'BillingAddresses' => ['Countries'], 'ShippingAddresses' => ['Countries']],
            'status' => true
        ]);
        $calculator = $this->ShopOrders->calculateOrderCosts($shopOrder);
        $this->set('shopOrder', $shopOrder);
        $this->set('calculator', $calculator);
        $this->set('_serialize', ['shopOrder']);
    }

    public function confirm($id = null)
    {
        $order = $this->ShopOrders->get($id, ['contain' => []]);

        if ($order->status >= ShopOrdersTable::ORDER_STATUS_CONFIRMED) {
            $this->Flash->error(__d('shop', 'Failed to confirm order: Invalid order status'));

        } elseif ($this->ShopOrders->confirmOrder($order)) {
            $this->Flash->success(__d('shop', 'Order confirmed'));

        } else {
            $this->Flash->error(__d('shop', 'Failed to create invoice'));
        }

        $this->redirect($this->referer(['action' => 'view', $id]));
    }

    public function invoice($id = null)
    {
        $order = $this->ShopOrders->get($id, ['contain' => []]);

        if ($order->status != ShopOrdersTable::ORDER_STATUS_CONFIRMED) {
            $this->Flash->error(__d('shop', 'Failed to create invoice: Invalid order status'));

        } elseif ($this->ShopOrders->assignInvoiceNr($order)) {
            $this->Flash->success(__d('shop', 'Invoice created'));

        } else {
            $this->Flash->error(__d('shop', 'Failed to create invoice'));
        }

        $this->redirect($this->referer(['action' => 'view', $id]));
    }

    public function payed($id = null)
    {
        $order = $this->ShopOrders->get($id, ['contain' => []]);

        if ($order->status >= ShopOrdersTable::ORDER_STATUS_PAYED) {
            $this->Flash->error(__d('shop', 'Failed to change order status: Invalid status'));

        } elseif ($this->ShopOrders->updateStatus($order, ShopOrdersTable::ORDER_STATUS_PAYED)) {
            $this->Flash->success(__d('shop', 'Status updated'));

        } else {
            $this->Flash->error(__d('shop', 'Failed to update status'));
        }

        $this->redirect($this->referer(['action' => 'view', $id]));
    }

    /**
     * @return array
     */
    public function implementedEvents()
    {
        $events = parent::implementedEvents();
        return $events;
    }
}
