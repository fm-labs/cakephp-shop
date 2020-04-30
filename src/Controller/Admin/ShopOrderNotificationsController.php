<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

/**
 * ShopOrderNotifications Controller
 *
 * @property \Shop\Model\Table\ShopOrderNotificationsTable $ShopOrderNotifications
 */
class ShopOrderNotificationsController extends AppController
{
    /**
     * @var array
     */
    public $actions = [
        'index' => 'Admin.Index',
        'view' => 'Admin.View',
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->Action->execute();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Order Notification id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->Action->execute();
    }
}
