<?php
namespace Shop\Controller\Admin;

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
            'contain' => ['ShopCustomers', 'ShopProducts'],
            'limit' => 100,
            'order' => ['shop_customer_id' => 'ASC', 'shop_product_id' => 'ASC']
        ];

        $this->set('fields', [
            'id' => [],
            'shop_customer' => ['formatter' => function ($val, $row, $args, $view) {
                return ($val)
                    ? $view->Html->link($val->display_name, ['controller' => 'ShopCustomers', 'action' => 'view', $val->id])
                    : __d('shop', 'All customers');
            }],
            'shop_product' => ['formatter' => function ($val, $row, $args, $view) {
                return ($val)
                    ? $view->Html->link($val->title, ['controller' => 'ShopProducts', 'action' => 'view', $val->id])
                    : __d('shop', 'All products');
            }],
            'valuetype' => [],
            'value' => [],
            'min_amount',
            'is_published'
        ]);
        $this->set('fields.blacklist', ['publish_start', 'publish_end']);

        $this->Action->execute();
    }

    public function add()
    {
        $this->set('types', $this->ShopCustomerDiscounts->listTypes());
        $this->set('valuetypes', $this->ShopCustomerDiscounts->listValueTypes());
        //$this->set('shopCustomers', $this->ShopCustomerDiscounts->ShopCustomers->find('list'));
        //$this->set('shopProducts', $this->ShopCustomerDiscounts->ShopProducts->find('list'));
        $this->set('fields.blacklist', ['publish_start', 'publish_end']);
        $this->Action->execute();
    }

    public function edit()
    {
        $this->set('types', $this->ShopCustomerDiscounts->listTypes());
        $this->set('valuetypes', $this->ShopCustomerDiscounts->listValueTypes());
        //$this->set('shopCustomers', $this->ShopCustomerDiscounts->ShopCustomers->find('list'));
        //$this->set('shopProducts', $this->ShopCustomerDiscounts->ShopProducts->find('list'));
        $this->set('fields.blacklist', ['publish_start', 'publish_end']);
        $this->Action->execute();
    }
}
