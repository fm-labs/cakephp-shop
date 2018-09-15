<?php
namespace Shop\Controller\Admin;

use Shop\Controller\Admin\AppController;

/**
 * ShopCustomerDiscounts Controller
 *
 * @property \Shop\Model\Table\ShopCustomerDiscountsTable $ShopCustomerDiscounts
 */
class ShopCustomerDiscountsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ShopCustomers', 'ShopProducts']
        ];

        $this->set('fields', [
            'id' => [],
            'shop_customer' => ['formatter' => function ($val, $row, $args, $view) {
                return ($val) ? $val->display_name : null;
            }],
            'shop_product_id' => ['formatter' => function ($val, $row, $args, $view) {
                return ($val) ? $row->shop_product->title : null;
            }],
            'type' => [],
            'valuetype' => [],
            'value' => [],

        ]);
        $this->set('fields.whitelist', true);
        $this->Action->execute();
    }
}
