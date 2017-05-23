<?php
namespace Shop\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Hash;
use Shop\Lib\EuVatNumber;

/**
 * ShopAddress Entity.
 *
 * @property int $id
 * @property int $shop_customer_id
 * @property \Shop\Model\Entity\ShopCustomer $shop_customer
 * @property string $type
 * @property string $first_name
 * @property string $last_name
 * @property bool $is_company
 * @property string $company_name
 * @property string $company_taxid
 * @property string $street1
 * @property string $street2
 * @property string $zipcode
 * @property string $city
 * @property string $country
 * @property string $country_iso2
 * @property string $phone
 * @property string $email
 * @property string $email_secondary
 * @property bool $is_archived
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class ShopAddress extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
        'shop_order_id' => false,
        'created' => false,
        'modified' => false,
    ];

    protected $_virtual = [
        'name',
        'display_name',
        'oneline',
        //'formatted'
    ];

    protected function _getName()
    {
        return sprintf("%s %s", $this->first_name, $this->last_name);
    }

    protected function _getDisplayName()
    {
        if ($this->company_name) {
            return sprintf("%s, %s, %s", $this->company_name, $this->last_name, $this->first_name);
        }
        if ($this->last_name && $this->first_name) {
            return sprintf("%s, %s", $this->last_name, $this->first_name);
        }

    }

    protected function _getOneline()
    {
        if ($this->is_company) {
            return sprintf("%s, %s, %s %s (Company)",
                $this->company_name,
                $this->street,
                $this->zipcode,
                $this->city
            );
        }

        return sprintf("%s %s, %s, %s %s",
            $this->first_name,
            $this->last_name,
            $this->street,
            $this->zipcode,
            $this->city
        );
    }

    protected function _getShort()
    {
        if ($this->company_name) {
            return sprintf("%s, %s %s",
                $this->last_name,
                $this->first_name,
                $this->company_name);
        }

        return sprintf("%s, %s",
            $this->last_name,
            $this->first_name);
    }

    protected function _getFormatted()
    {
        if ($this->company_name) {
            return sprintf("%s\n%s\n%s %s\n%s",
                $this->company_name,
                $this->street,
                $this->zipcode,
                $this->city,
                $this->country
            );
        }

        return sprintf("%s %s\n%s\n%s %s\n%s",
            $this->first_name,
            $this->last_name,
            $this->street,
            $this->zipcode,
            $this->city,
            $this->country
        );

    }

    protected function _setTaxid($val)
    {
        //@TODO Add support for non-EU taxids
        return ($val) ? EuVatNumber::normalize($val) : null;
    }


    public function extractAddress()
    {
        $props = ['company_name', 'first_name', 'last_name', 'street', 'street2', 'zipcode', 'city', 'country', 'country_id', 'taxid'];
        return $this->extract($props);
    }


    /**
     * !! Legacy method use by Migration shell !!
     * !! Do not remove yet !!
     *
     * @param $array
     * @param null $prefix
     * @return array
     * @deprecated
     */
    public static function xtractAddress($array, $prefix = null)
    {
        $address = [];
        foreach (['is_company', 'company_name', 'first_name', 'last_name', 'street', 'zipcode', 'city', 'country', 'taxid'] as $field) {
            $_field = $field;
            if ($prefix) {
                $_field = $prefix . $field;
            }

            $value = null;
            if (array_key_exists($_field, $array)) {
                $value = $array[$_field];
            }

            $address[$field] = $value;
        }

        return $address;
    }
}
