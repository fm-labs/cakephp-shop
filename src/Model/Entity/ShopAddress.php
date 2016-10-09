<?php
namespace Shop\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Hash;

/**
 * ShopAddress Entity.
 *
 * @property int $id
 * @property int $shop_customer_id
 * @property \Shop\Model\Entity\ShopCustomer $shop_customer
 * @property string $type
 * @property string $refscope
 * @property int $refid
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
    ];

    protected $_virtual = [
        'name',
        'oneline',
        'formatted'
    ];

    protected function _getName()
    {
        if ($this->_properties['is_company']) {
            return $this->_properties['company_name'];
        }
        return sprintf("%s, %s", $this->_properties['last_name'], $this->_properties['first_name']);
    }

    protected function _getOneline()
    {


        if ($this->_properties['is_company']) {
            return sprintf("%s, %s, %s %s (Company)",
                $this->_properties['company_name'],
                $this->_properties['street'],
                $this->_properties['zipcode'],
                $this->_properties['city']
            );
        }

        return sprintf("%s %s, %s, %s %s",
            $this->_properties['first_name'],
            $this->_properties['last_name'],
            $this->_properties['street'],
            $this->_properties['zipcode'],
            $this->_properties['city']
        );
    }

    protected function _getFormatted()
    {
        //@TODO Refactor with self::formatAddress()
        if ($this->_properties['is_company']) {
            return sprintf("%s\n%s\n%s %s\n%s",
                $this->_properties['company_name'],
                $this->_properties['street'],
                $this->_properties['zipcode'],
                $this->_properties['city'],
                $this->_properties['country']
            );
        }

        return sprintf("%s %s\n%s\n%s %s\n%s",
            $this->_properties['first_name'],
            $this->_properties['last_name'],
            $this->_properties['street'],
            $this->_properties['zipcode'],
            $this->_properties['city'],
            $this->_properties['country']
        );

    }

    public static function formatAddress($address) {

        $is_company = $company_name = $first_name = $last_name = $street = $zipcode = $city = $country = null;
        extract($address, EXTR_IF_EXISTS);

        if ($is_company) {
            return sprintf("%s\n%s\n%s %s\n%s",
                $company_name,
                $street,
                $zipcode,
                $city,
                $country
            );
        }

        return sprintf("%s %s\n%s\n%s %s\n%s",
            $first_name,
            $last_name,
            $street,
            $zipcode,
            $city,
            $country
        );
    }

    public static function extractAddress($array, $prefix = null)
    {
        $address = [];
        foreach (['is_company', 'company_name', 'first_name', 'last_name', 'street', 'zipcode', 'city', 'country'] as $field) {
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
