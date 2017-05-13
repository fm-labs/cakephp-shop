<?php
namespace Shop\Model\Entity;

use Cake\Auth\AbstractPasswordHasher;
use Cake\ORM\Entity;

/**
 * ShopCustomer Entity.
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $email_verification_code
 * @property bool $email_verified
 * @property bool $is_guest
 * @property bool $is_blocked
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \Shop\Model\Entity\ShopAddress[] $shop_addresses
 * @property \Shop\Model\Entity\ShopOrder[] $shop_orders
 */
class ShopCustomer extends Entity
{

    public static $passwordHasherClass = 'Cake\\Auth\\DefaultPasswordHasher';

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
        'password1' => false,
        'password2' => false,
    ];

    protected $_virtual = [
        'display_name',
        'is_demo'
    ];

    protected function _setEmail($email)
    {
        return strtolower($email);
    }

    /**
     * @return AbstractPasswordHasher
     */
    public function getPasswordHasher()
    {
        return (new static::$passwordHasherClass());
    }

    protected function _getDisplayName()
    {
        if ($this->first_name && $this->last_name) {
            return sprintf("%s %s", $this->first_name, $this->last_name);
        }

        return $this->_properties['email'];
    }

    protected function _getIsGuest()
    {
        return (isset($this->_properties['user_id'])) ? false : true;
    }

    protected function _getIsDemo()
    {
        return (preg_match('/@example\.org$/', $this->email));
    }

}
