<?php
namespace Shop\Controller\Admin;

use Shop\Controller\Admin\AppController;

/**
 * ShopCustomers Controller
 *
 * @property \Shop\Model\Table\ShopCustomersTable $ShopCustomers
 */
class ShopCustomersController extends AppController
{
    /**
     * @var string
     */
    public $modelClass = "Shop.ShopCustomers";

    /**
     * @var array
     */
    public $paginate = [
        'limit' => 100,
        'order' => ['ShopCustomers.last_name' => 'ASC', 'ShopCustomers.first_name' => 'ASC'],
        'contain' => ['Users']
    ];

    /**
     * @var array
     */
    public $actions = [
        'index'     => 'Backend.Index',
        'view'      => 'Backend.View',
        'add'       => 'Backend.Add',
        'edit'      => 'Backend.Edit',
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {

        $this->paginate = [
            'order' => ['ShopCustomers.last_name'],
            'contain' => ['Users']
        ];

        $this->set('fields.whitelist', ['id', 'user', 'email', 'display_name']);
        $this->set('fields.blacklist', ['password', 'created', 'modified']);
        $this->set('fields', [
            'display_name',
            'user' => ['formatter' => function ($val, $row, $args, $view) {
                //return ($val) ? $view->Html->link($val->display_name, ['plugin' => 'User', 'controller' => 'Users', 'action' => 'view', $val->id]) : null;
                return ($val) ? $val->display_name : $val;
            }]
            //'user' => ['formatter' => ['related', 'display_name'], 'type' => 'object']
        ]);

        $this->set('paginate', true);
        $this->Action->execute();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Customer id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $entity = $this->ShopCustomers->get($id, ['contain' => [ 'Users', /*'Countries',*/ 'ShopCustomerAddresses' => ['Countries'], 'ShopCustomerDiscounts']]);
        $this->set('entity', $entity);
        $this->set('related', [
            'ShopCustomerAddresses' => [
                'fields' => ['type', 'is_company', 'company_name', 'taxid', 'first_name', 'last_name', 'street', 'zipcode', 'city', 'country.name']
            ],
            'ShopCustomerDiscounts' => []]);
        $this->Action->execute();
    }
}
