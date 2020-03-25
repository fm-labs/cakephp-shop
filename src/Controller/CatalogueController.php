<?php
declare(strict_types=1);

namespace Shop\Controller;

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
