<?php

namespace Shop\View\Cell;

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Form\Form;
use Cake\ORM\Query;
use Cake\View\Cell;
use Shop\Core\Cart\Form\AddToCartForm;
use Shop\Core\Product\ShopProductInterface;

/**
 * Class AddToCartCell
 *
 *
 *
 * @package Shop\src\View\Cell
 */
class AddToCartCell extends Cell
{

    public $modelClass = "Shop.ShopProducts";

    /**
     * @var ShopProductInterface
     */
    public $shopProduct;

    public $shopProductVersions;

    public $params = [];

    public $inputs = [];

    public $inputsOptions = [];

    public $type = "form";

    /**
     * @var string Class path of AddToCartForm
     */
    public $formClass = '\Shop\Core\Cart\Form\AddToCartForm';

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = ['shopProduct', 'formClass'];

    public function _initInputs()
    {
        $formOptions = [
            'idPrefix' => $this->shopProduct->id,
            'url' => ['plugin' => 'Shop', 'controller' => 'Cart', 'action' => 'add', $this->shopProduct->id ],
        ];
        $formInputsOptions = ['legend' => false, 'fieldset' => false];
        $inputs = [];

        // Product version
        $inputs['refscope'] = [
            'type' => 'hidden',
            'value' => 'Shop.ShopProducts',
        ];
        $inputs['refid'] = [
            'type' => 'hidden',
            'value' => $this->shopProduct->id,
        ];

        // Multiple product versions
        $productVersions = $this->_getProductVersions()->find('list')->toArray();
        if ($productVersions) {
            $inputs['refid'] = [
                'type' => 'select',
                'options' => $productVersions,
                'label' => __d('shop', 'Product version'),
            ];
        }

        // Qty
        $inputs['amount'] = [
            'type' => 'hidden',
            'default' => 1,
            'label' => false,
        ];

        if ($this->params['qty'] !== false) {
            $inputs['amount'] = [
                'type' => 'select',
                'options' => $this->_getQtyOptions(),
                'default' => 1,
                'label' => __d('shop', 'Quantity'),
            ];
        }
        //unset($params['qty']);

        // Additional order item params
        foreach ($this->params as $pKey => $pOpts) {
            if ($pKey === 'qty') {
                continue;
            }

            if (is_numeric($pKey)) {
                $pKey = $pOpts;
                $pOpts = [];
            }
            if (!isset($inputs[$pKey])) {
                $inputs[$pKey] = $pOpts;
            }
        }

        $this->inputs = $inputs;
        $this->inputsOptions = $formInputsOptions;
        $this->formOptions = $formOptions;
    }

    public function display($params = [])
    {
        $this->_checkProduct();

        $this->params = $params + ['qty' => null];
        $this->_initInputs();

        $auth = $this->_checkAuth();
        if (!$auth) {
            $formOptions['disabled'] = 'disabled';
            //@TODO disable inputs
        }

        $form = $this->_createForm($this->formOptions, $this->inputs, $this->inputsOptions);

        $this->set('type', $this->type);
        $this->set('auth', $auth);
        $this->set('params', $params);
        $this->set('product', $this->shopProduct);
        //$this->set('productVersions', $productVersions); // deprecated

        $this->set('form', $form);
        $this->set('formOptions', $this->formOptions);
        $this->set('formInputs', $this->inputs);
        $this->set('formInputsOptions', $this->inputsOptions);
    }

    public function button($params = [])
    {
        $params['qty'] = false;
        $params['parent'] = false;

        $this->type = 'button';
        $this->template = 'display';
        $this->display($params);
    }

    /**
     * Default display method.
     *
     * @return void
     * @deprecated
     */
    public function ___display($params = [])
    {
        $this->_checkProduct();

        $inputs = [];
        $inputs['refid'] = [
            'type' => 'hidden',
            'value' => $this->shopProduct->id,
        ];
        $inputs['refscope'] = [
            'type' => 'hidden',
            'value' => 'shop_product',
        ];
        $inputs['amount'] = [
            'type' => 'hidden',
            'default' => 1,
            'label' => false,
        ];

        $formOptions = ['url' => ['plugin' => 'Shop', 'controller' => 'Cart', 'action' => 'add', $this->shopProduct->id ]];
        $formInputsOptions = ['legend' => false, 'fieldset' => false];
        //$form = new AddToCartForm();

        $auth = $this->_checkAuth();
        if (!$auth) {
            $formOptions['disabled'] = 'disabled';
            //@TODO disable inputs
        }

        $form = $this->_createForm($formOptions, $inputs, $formInputsOptions);

        $this->set('auth', $auth);
        $this->set('params', $params);
        $this->set('product', $this->shopProduct);

        $this->set('form', $form);
        $this->set('formOptions', $formOptions);
        $this->set('formInputs', $inputs);
        $this->set('formInputsOptions', $formInputsOptions);
    }

    /**
     * @param $formOptions
     * @param $formInputOptions
     * @return Form
     */
    protected function _createForm($formOptions, $formInputs, $formInputOptions)
    {
        if (!class_exists($this->formClass)) {
            throw new Exception('AddToCartForm class not found in ' . $this->formClass);
        }

        $form = new $this->formClass();

        return $form;
    }

    protected function _checkProduct()
    {
        if (!$this->shopProduct) {
            throw new \LogicException('AddToCartCell: Shop product missing');
        }

        //if (!$this->shopProduct->isBuyable()) {
        //    throw new \LogicException('AddToCartCell: Product not buyable'); //@TODO Replace with ProductNotBuyableException
        //}
    }

    protected function _checkAuth()
    {
        if (Configure::read('Shop.Cart.requireAuth') && !$this->request->getSession()->read('Shop.Customer.id')) {
            return false;
        }

        return true;
    }

    /**
     * @return Query
     */
    protected function _getProductVersions()
    {
        $this->loadModel('Shop.ShopProducts');

        return $this->ShopProducts->findPublishedChildren($this->shopProduct->id);
    }

    /**
     * @return array
     * @TODO Get quantity options from product
     * @TODO Implement min/max quantity constraints
     */
    protected function _getQtyOptions()
    {
        $qtyOptions = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10];

        return $qtyOptions;
    }
}
