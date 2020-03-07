<?php

namespace Shop\Controller;

use Cake\Core\Configure;

/**
 * Class ShopController
 *
 * This class is reserved for later use.
 * Intended as a future shop entry point.
 *
 * Meanwhile use the shop categories controller as main entry point
 *
 * @package Shop\Controller
 */
class ShopController extends AppController
{
    public $modelClass = false;

    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow();
    }

    public function index()
    {
        $this->redirect(['controller' => 'Categories', 'action' => 'index']);
    }
}
