<?php
namespace Shop\Controller\Admin;

use Cake\Core\Configure;
use Cake\Event\Event;
use Shop\Model\Entity\ShopOrder;

/**
 * ShopOrders Controller
 *
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 * @property \Backend\Controller\Component\ActionComponent $Action
 */
class ShopOrdersController extends AppController
{
    /**
     * @var array
     */
    public $actions = [
        'index'     => 'Backend.Index',
        //'index2'     => 'Backend.Index',
        'view'      => 'Backend.View',
        //'add'       => 'Backend.Add',
        'edit'      => 'Backend.Edit',
        //'print_order' => 'Shop.PrintOrder'
    ];

    /**
     * Initialize method
     */
    public function initialize()
    {
        parent::initialize();

        $this->Action->registerInline('storno', ['scope' => ['form', 'table'], 'attrs' => ['data-icon' => 'trash']]);
        //$this->Action->registerInline('summary');
        //$this->loadComponent('RequestHandler');
    }

    public function storno($id = null)
    {
        $this->Flash->error("Not implemented yet");
        $this->redirect($this->referer(['action' => 'index']));
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
            'submitted', 'nr_formatted',
            'shop_customer' => ['formatter' => ['related', 'display_name'], 'type' => 'object'],
            'order_value_total_formatted' => ['label' => 'Total Value', 'formatter' => 'currency' , 'class' => 'text-right'],
            'status__status' => ['label' => 'Status', 'formatter' => 'status', 'type' => 'object']
        ]);

        $this->Action->execute();
    }
    /**
     * Index method
     *
     * @return void
     * @deprecated
     */
    public function index2()
    {
        $this->paginate = [
            'contain' => ['ShopCustomers', 'ShopOrderAddresses' => ['Countries'], 'BillingAddresses' => ['Countries'], 'ShippingAddresses' => ['Countries']],
            'conditions' => ['ShopOrders.is_temporary' => false],
            'order' => ['ShopOrders.id' => 'DESC'],
            'status' => true,
        ];

        $this->set('helpers', ['Banana.Status', 'Bootstrap.Button', 'Bootstrap.Icon']);
        $this->set('filter', false);
        $this->set('fields.whitelist', true);
        $this->set('fields', [
            //'id' => [],
            'submitted' => [
            ],
            'nr_formatted' => ['label' => __d('shop', 'Order'), 'formatter' => function ($val, $row, $args, $view) {

                if ($val) {
                    //$html = $view->Html->link($val, ['action' => 'view', $row->id]);
                    //$html .= "&nbsp";
                    //$view->Icon->create('gear')
                    $html = $view->Button->create($val, [
                        'size' => 'xs',
                        'dropdown' => [
                            'shop_order_printview' => [
                                'title' => __d('shop', 'Printview'),
                                'url' => ['action' => 'printview', $row->id],
                                'attrs' => ['target' => '_blank', 'data-icon' => 'print']
                            ],
                            'shop_order_pdfview' => [
                                'title' => __d('shop', 'View PDF'),
                                'url' => ['action' => 'pdfview', $row->id],
                                'attrs' => ['target' => '_blank', 'data-icon' => 'file-pdf-o']
                            ],
                            'shop_order_pdfdl' => [
                                'title' => __d('shop', 'Download PDF'),
                                'url' => ['action' => 'pdfdownload', $row->id],
                                'attrs' => ['target' => '_blank', 'data-icon' => 'file-pdf-o']
                            ]
                        ],
                    ]);
                    return $html;
                }

                //return ($val) ? $view->Html->link($val, ['action' => 'view', $row->id]) : null;
            }],
            'invoice_nr_formatted' => ['label' => __d('shop', 'Invoice'), 'formatter' => function ($val, $row, $args, $view) {

                if ($val) {
                    return $view->Button->create($val, [
                        'url' => false,
                        'size' => 'xs',
                        'dropdown' => [
                            'shop_order_printview' => [
                                'title' => __d('shop', 'Printview'),
                                'url' => ['action' => 'printview', $row->id, 'mode' => 'invoice'],
                                'attrs' => ['target' => '_blank', 'data-icon' => 'print']
                            ],
                            'shop_order_pdfview' => [
                                'title' => __d('shop', 'View PDF'),
                                'url' => ['action' => 'pdfview', $row->id, 'mode' => 'invoice'],
                                'attrs' => ['target' => '_blank', 'data-icon' => 'file-pdf-o']
                            ],
                            'shop_order_pdfdl' => [
                                'title' => __d('shop', 'Download PDF'),
                                'url' => ['action' => 'pdfdownload', $row->id, 'mode' => 'invoice'],
                                'attrs' => ['target' => '_blank', 'data-icon' => 'file-pdf-o']
                            ]
                        ],
                        'split' => false
                    ]);
                }

                //return ($val) ? $view->Html->link($val, ['action' => 'view', $row->id, 'mode' => 'invoice']) : null;
            }],
            'shop_customer_id' => ['formatter' => function ($val, $row, $args, $view) {
                return $view->Html->link(
                    $row->shop_customer->display_name,
                    ['controller' => 'ShopCustomers', 'action' => 'view', $row->shop_customer->id]
                );
            }],
            'order_value_total' => [
                'class' => 'right',
                'formatter' => ['currency' => ['currency' =>  'EUR']],
            ],
            '_status' => ['formatter' => function ($val, $row, $args, $view) {
                return $view->Status->label($val);
            }],
            //'payment_status' => [],
            //'shipping_status' => [],
        ]);

        $this->set('rowActionCallback', function(ShopOrder $order) {

        });

        /*
        $this->set('rowActions', [
            'print' => [__d('shop', 'Printview'), ['action' => 'printview', ':id'], ['target' => '_blank', 'data-icon' => 'print']],
            'pdfview' => [__d('shop', 'View PDF'), ['action' => 'pdfview', ':id'], ['target' => '_blank', 'data-icon' => 'file-pdf-o']],
            'pdfdownload' => [__d('shop', 'Download PDF'), ['action' => 'pdfdownload', ':id'], ['target' => '_blank', 'data-icon' => 'file-pdf-o']]
        ]);
        */

        $this->Action->execute();
    }

    public function buildEntityActions(Event $event)
    {
        //$event->data['actions']['print'] = [__d('shop', 'Printview'), ['action' => 'printview', ':id'], ['target' => '_blank', 'data-icon' => 'print']];
        //$event->data['actions']['pdfview'] = [__d('shop', 'View PDF'), ['action' => 'pdfview', ':id'], ['target' => '_blank', 'data-icon' => 'file-pdf-o']];
        //$event->data['actions']['pdfdownload'] = [__d('shop', 'Download PDF'), ['action' => 'pdfdownload', ':id'], ['target' => '_blank', 'data-icon' => 'file-pdf-o']];
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
            'contain' => ['ShopCustomers' => ['Users'], 'ShopOrderItems', 'BillingAddresses' => ['Countries'], 'ShippingAddresses' => ['Countries'], 'ShopOrderTransactions', 'ShopOrderAddresses'],
            'status' => true
        ]);
        $this->set('entity', $shopOrder);
        $this->set('_serialize', 'entity');

        $this->set([
            'title' => $shopOrder->get('nr_formatted'),
            'model' => 'Shop.ShopOrders',
            'fields.whitelist' => true,
            'fields' => [
                '_status' => ['formatter' => function($val, $row, $args, $view) {
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
            'ShopOrderTransactions' => [
                'fields' => [
                    'id', 'type', 'engine', 'currency_code', 'value', 'status', 'ext_txnid', 'ext_status', 'last_message', 'is_test'
                ]
            ],
            'ShopCustomers',
            'ShopOrderItems',
            'ShopOrderAddresses',
            'BillingAddresses',
            'ShippingAddresses'
        ]);

        /*
        $this->set('tabs', [
            //'summary' => [
            //    'title' => __('Summary'),
            //    'url' => ['controller' => 'ShopOrders', 'action' => 'summary', $shopOrder->id]
            //],
            'order-items' => [
                'title' => __('Order Items'),
                'url' => ['controller' => 'ShopOrderItems', 'action' => 'index', 'order_id' => $shopOrder->id]
            ],
            'order-transactions' => [
                'title' => __('Transactions'),
                'url' => ['controller' => 'ShopOrderTransactions', 'action' => 'index', 'shop_order_id' => $shopOrder->id]
            ],
            //'raw' => [
            //    'title' => __('Raw Data'),
            //    'url' => ['plugin' => 'Backend', 'controller' => 'Entity', 'action' => 'view', 'Shop.ShopOrders', $shopOrder->id]
            //]
        ]);
        */

        //$this->noActionTemplate = true;
        $this->Action->execute();
    }


    public function summary($id = null)
    {
        $shopOrder = $this->ShopOrders->get($id, [
            'contain' => ['ShopCustomers' => ['Users'], 'ShopOrderItems', 'BillingAddresses' => ['Countries'], 'ShippingAddresses' => ['Countries']],
            'status' => true
        ]);
        $this->set('shopOrder', $shopOrder);
        $this->set('_serialize', 'shopOrder');
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
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
    public function add()
    {
        $shopOrder = $this->ShopOrders->newEntity();
        if ($this->request->is('post')) {
            $shopOrder = $this->ShopOrders->patchEntity($shopOrder, $this->request->data);
            if ($this->ShopOrders->save($shopOrder)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop order')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop order')));
            }
        }
        $shopCustomers = $this->ShopOrders->ShopCustomers->find('list', ['limit' => 200]);
        $billingAddresses = $this->ShopOrders->ShopCustomerAddresses->find('list', ['limit' => 200])->toArray();
        $shippingAddresses = $this->ShopOrders->ShopCustomerAddresses->find('list', ['limit' => 200])->toArray();
        $this->set(compact('shopOrder', 'shopCustomers', 'billingAddresses', 'shippingAddresses'));
        $this->set('_serialize', ['shopOrder']);
    }
     */

    /**
     * Edit method
     *
     * @param string|null $id Shop Order id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopOrder = $this->ShopOrders->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopOrder = $this->ShopOrders->patchEntity($shopOrder, $this->request->data);
            if ($this->ShopOrders->save($shopOrder)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop order')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop order')));
            }
        }
        //$billingAddresses = $this->ShopOrders->BillingAddresses->find('list', ['limit' => 200])->where(['BillingAddresses.shop_customer_id' => $shopOrder->shop_customer_id])->toArray();
        //$shippingAddresses = $this->ShopOrders->ShippingAddresses->find('list', ['limit' => 200])->toArray();
        $this->set(compact('shopOrder', 'shopCustomers' /*, 'billingAddresses', 'shippingAddresses' */));
        $this->set('_serialize', ['shopOrder']);

        $this->noActionTemplate = true;
        $this->Action->execute();
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Order id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopOrder = $this->ShopOrders->get($id);
        if ($this->ShopOrders->delete($shopOrder)) {
            $this->Flash->success(__d('shop', 'The {0} has been deleted.', __d('shop', 'shop order')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be deleted. Please, try again.', __d('shop', 'shop order')));
        }

        return $this->redirect(['action' => 'index']);
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
     * @return array
     */
    public function implementedEvents()
    {
        $events = parent::implementedEvents();
        /*
        $events['Backend.DataTable.setup'] = ['callable' => function (Event $event) {
            debug("hello");
        }];
        $events['Backend.Controller.buildEntityActions'] = ['callable' => function (Event $event) {
            $event->data['actions']['print'] = [__d('shop', 'Printview'), ['action' => 'printview', ':id'], ['target' => '_blank', 'data-icon' => 'print']];
            $event->data['actions']['pdfview'] = [__d('shop', 'View PDF'), ['action' => 'pdfview', ':id'], ['target' => '_blank', 'data-icon' => 'file-pdf-o']];
            $event->data['actions']['pdfdownload'] = [__d('shop', 'Download PDF'), ['action' => 'pdfdownload', ':id'], ['target' => '_blank', 'data-icon' => 'file-pdf-o']];
        }];
        */

        return $events;
    }
}
