<?php
declare(strict_types=1);

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
     * Options:
     * - countries (array): List of countries
     * - company (bool): Enable country_name field (Fallback to Shop.Address.useCompanyName config value)
     * - taxid (bool): Enable taxid field (Fallback to Shop.Address.useTaxId config value)
     * - submit (string): Custom submit button label
     *
     * @params $address ShopAddress
     * @param $options array
     *
     * @return void
     */
    public function display($address = null, array $options = [])
    {
        $options += ['countries' => null, 'company' => null, 'taxid' => null, 'submit' => null];

        if ($options['company'] === null) {
            $options['company'] = Shop::config('Shop.Address.useCompanyName');
        }
        if ($options['taxid'] === null) {
            $options['taxid'] = Shop::config('Shop.Address.useTaxId');
        }

        // countries
        $countries = $options['countries'];
        $countries = $countries ?: $this->loadModel('Shop.ShopCountries')->find('list')->find('published')->all()->toArray();
        unset($options['countries']);

        $this->set(compact('address', 'countries', 'options'));
    }
}
