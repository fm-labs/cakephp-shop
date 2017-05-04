<?php
namespace Shop\Model\Entity;

use Cake\ORM\Entity;

/**
 * ShopOrderAddress Entity.
 *
 * @property int $id
 * @property int $shop_order_id
 * @property \Shop\Model\Entity\ShopOrder $shop_order
 * @property int $shop_customer_address_id
 * @property \Shop\Model\Entity\ShopCustomerAddress $shop_customer_address
 * @property string $type
 * @property bool $is_company
 * @property string $taxid
 * @property string $first_name
 * @property string $last_name
 * @property string $street
 * @property string $street2
 * @property string $zipcode
 * @property string $city
 * @property \Shop\Model\Entity\Country $country
 * @property int $country_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class ShopOrderAddress extends Entity
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
        'short',
        'oneline',
        'formatted'
    ];

    protected function _getName()
    {
        return sprintf("%s, %s",
            $this->last_name,
            $this->first_name);
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

    protected function _getOneline()
    {
        if ($this->company_name) {
            return sprintf("%s, %s %s, %s, %s %s",
                $this->company_name,
                $this->last_name,
                $this->first_name,
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

    protected function _getFormatted()
    {
        //@TODO Refactor with self::formatAddress()
        if ($this->is_company) {
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
}
