<?php
namespace Shop\Controller\Admin;

/**
 * ShopCountries Controller
 *
 * @property \Shop\Model\Table\ShopCountriesTable $ShopCountries
 */
class ShopCountriesController extends AppController
{
    /**
     * @var array
     */
    public $paginate = [
        'order' => ['ShopCountries.priority' => 'DESC', 'ShopCountries.iso2' => 'ASC'],
        'limit' => 250,
        'maxLimit' => 250,
    ];

    /**
     * @var array
     */
    public $actions = [
        'index' => 'Backend.Index',
        'view' => 'Backend.View',
        'add' => 'Backend.Add',
        'edit' => 'Backend.Edit',
        'delete' => 'Backend.Delete',
        'publish' => 'Backend.Publish',
        'unpublish' => 'Backend.Unpublish',
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'order' => ['ShopCountries.is_published' => 'DESC', 'ShopCountries.name_de' => 'ASC'],
            'limit' => 200,
            'maxLimit' => 200,
        ];

        $this->set('paginate', true);
        $this->set('limit', 200);

        $this->set('fields.whitelist', ['id', 'iso2', 'iso3', 'name_de', 'name', 'is_published']);
    }
}
