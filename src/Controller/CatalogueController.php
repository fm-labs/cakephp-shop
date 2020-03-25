<?php

namespace Shop\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;

/**
 * Class ShopController
 *
 * @package Shop\Controller
 */
class CatalogueController extends AppController
{
    public $modelClass = "";

    public function initialize(): void
    {
        parent::initialize();

        $this->Auth->allow(['index']);
    }

    public function index()
    {
        $categoryIds = "";

        $products = $this->ShopProducts->find('all')
            ->where('');
    }
}
