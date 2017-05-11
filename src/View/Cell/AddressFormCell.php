<?php
namespace Shop\View\Cell;

use Cake\View\Cell;
use Shop\Lib\Shop;

/**
 * AddressFormCell cell
 *
 */
class AddressFormCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display($address = null, array $options = [])
    {
        $options += ['countries' => null, 'company' => null, 'taxid' => null];

        if ($options['company'] === null) {
            $options['company'] = Shop::config('Shop.Address.useCompanyName');
        }
        if ($options['taxid'] === null) {
            $options['taxid'] = Shop::config('Shop.Address.useTaxId');
        }

        // countries
        $countries = $options['countries'];
        $countries = ($countries) ?: $this->loadModel('Shop.ShopCountries')->find('list')->find('published')->all()->toArray();
        unset($options['countries']);

        $this->set(compact('address', 'countries', 'options'));
    }
}
