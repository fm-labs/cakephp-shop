<?php
declare(strict_types=1);

namespace Shop\Controller;

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

    /**
     * {@inheritDoc}
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->Authentication->allowUnauthenticated(['index']);
    }

    public function index()
    {
        $this->redirect(['controller' => 'Categories', 'action' => 'index']);
    }
}
