<?php

namespace Shop\View\Cell;


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

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = ['shopProduct'];



    /**
     * Default display method.
     *
     * @return void
     */
    public function display($params = [])
    {
        $this->_checkProduct();

        $inputs = [];
        $inputs['refid'] = [
            'type' => 'hidden',
            'value' => $this->shopProduct->id
        ];
        $inputs['refscope'] = [
            'type' => 'hidden',
            'value' => 'shop_product'
        ];
        $inputs['amount'] = [
            'type' => 'hidden',
            'default' => 1,
            'label' => false
        ];

        $form = new AddToCartForm();
        $formOptions = ['url' => ['plugin' => 'Shop', 'controller' => 'Cart', 'action' => 'add', $this->shopProduct->id ]];
        $formInputsOptions = ['legend' => false, 'fieldset' => false];

        $this->set('params', $params);
        $this->set('product', $this->shopProduct);

        $this->set('form', $form);
        $this->set('formOptions', $formOptions);
        $this->set('formInputs', $inputs);
        $this->set('formInputsOptions', $formInputsOptions);
    }

    public function form($params = [])
    {
        $this->_checkProduct();

        $this->loadModel('Shop.ShopProducts');
        $params += ['qty' => null];

        $inputs = [];

        // Product version
        $inputs['refscope'] = [
            'type' => 'hidden',
            'value' => 'shop_product'
        ];
        $inputs['refid'] = [
            'type' => 'hidden',
            'value' => $this->shopProduct->id
        ];

        // Multiple product versions
        $productVersions = $this->_getProductVersions()->find('list')->toArray();
        if ($productVersions) {
            $inputs['refid'] = [
                'type' => 'select',
                'options' => $productVersions,
                'label' => __('Product version')
            ];
        }

        // Qty
        $inputs['amount'] = [
            'type' => 'hidden',
            'default' => 1,
            'label' => false
        ];

        if ($params['qty'] === true) {
            $inputs['amount'] = [
                'type' => 'select',
                'options' => $this->_getQtyOptions(),
                'default' => 1,
                'label' => __d('shop','Quantity')
            ];
        }


        $form = new AddToCartForm();

        $formOptions = ['url' => ['plugin' => 'Shop', 'controller' => 'Cart', 'action' => 'add', $this->shopProduct->id ]];
        $formInputsOptions = ['legend' => false, 'fieldset' => false];

        $this->set('params', $params);
        $this->set('product', $this->shopProduct);
        $this->set('productVersions', $productVersions); // deprecated

        $this->set('form', $form);
        $this->set('formOptions', $formOptions);
        $this->set('formInputs', $inputs);
        $this->set('formInputsOptions', $formInputsOptions);
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

    /**
     * @return Query
     */
    protected function _getProductVersions()
    {
        return $this->ShopProducts->findPublishedChildren($this->shopProduct->id);
    }

    protected function _getQtyOptions()
    {
        $qtyOptions = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10];
        return $qtyOptions;
    }
}