<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

/**
 * ShopCoupons Controller
 *
 * @property \Shop\Model\Table\ShopCouponsTable $ShopCoupons
 */
class ShopCouponsController extends AppController
{
    /**
     * @var string
     */
    public $modelClass = "Shop.ShopCoupons";

    /**
     * @var array
     */
    public $paginate = [
        'limit' => 100,
        'order' => ['is_published' => 'DESC', 'valid_until' => 'DESC']
    ];

    /**
     * @var array
     */
    public $actions = [
        'index'     => 'Admin.Index',
        'view'      => 'Admin.View',
        'add'       => 'Admin.Add',
        'edit'      => 'Admin.Edit',
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        //$this->set('fields.whitelist', []);
        //$this->set('fields.blacklist', []);
        //$this->set('fields', []);

        $this->Action->execute();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Coupon id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->Action->execute();
    }

    /**
     * Add method
     *
     * @return void
     */
    public function add()
    {

        $this->set('types', [
            'gift' => 'gift',
            'permanent' => 'permanent'
        ]);
        $this->set('valuetypes', [
           'total' => 'total',
           'percentage' => 'percentage',
        ]);

        $this->Action->execute();
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Coupon id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->set('types', [
            'gift' => 'gift',
        ]);
        $this->set('valuetypes', [
            'total' => 'total',
            'percent' => 'percent',
        ]);

        $this->Action->execute();
    }
}
