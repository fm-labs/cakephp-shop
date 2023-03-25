<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderTransaction;

/**
 * ShopOrderTransactions Controller
 *
 * @property \Shop\Model\Table\ShopOrderTransactionsTable $ShopOrderTransactions
 */
class ShopOrderTransactionsController extends AppController
{
    public $actions = [
        'index' => 'Admin.Index',
        'view' => 'Admin.View',
    ];

    public $paginate = [
        'order' => ['ShopOrderTransactions.id' => 'DESC'],
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        //$this->viewBuilder()->addHelper('Cupcake.Status');

        $dataUrl = ['rows' => 1];
        $query = $this->ShopOrderTransactions->find('all', ['status' => true, 'contain' => ['ShopOrders']]);
        if ($this->request->getQuery('shop_order_id')) {
            $dataUrl['shop_order_id'] = $this->request->getQuery('shop_order_id');
            $query->where(['ShopOrderTransactions.shop_order_id' => $this->request->getQuery('shop_order_id')]);
        }

        $this->set('fields', [
            'created' => ['formatter' => 'datetime'],
            //'shop_order' => ['formatter' => ['related', 'nr_formatted']],
            'shop_order' => ['formatter' => ['link', function (ShopOrder $order, ShopOrderTransaction $entity) {
                return [
                    'url' => ['controller' => 'ShopOrders', 'action' => 'view', $order->id],
                    'title' => $order->nr_formatted
                ];
            }]],
            'type', 'engine', 'currency_code',
            'value' => ['formatter' => ['currency', ['currency_field' => 'currency_code']]],
            'status__status' => ['label' => 'Status', 'formatter' => 'status', 'type' => 'object'],
            'ext_status', 'last_message', 'is_test',
        ]);
        $this->set('fields.whitelist', ['created', 'shop_order', 'type', 'engine', 'currency_code', 'value', 'status', 'ext_status', 'last_message', 'is_test']);
        $this->set('ajax', $dataUrl);
        $this->set('queryObj', $query);

        $this->Action->execute();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Order Transaction id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->set('related', ['ShopOrderTransactionNotifies']);
        $this->Action->execute();
    }
}
