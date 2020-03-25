<?php
declare(strict_types=1);

namespace Shop\Model\Entity;

use Cake\ORM\Entity;

/**
 * ShopCustomerDiscount Entity.
 *
 * @property int $id
 * @property int $shop_customer_id
 * @property \Shop\Model\Entity\ShopCustomer $shop_customer
 * @property int $shop_product_id
 * @property \Shop\Model\Entity\ShopProduct $shop_product
 * @property string $type
 * @property string $valuetype
 * @property float $value
 * @property bool $is_published
 * @property \Cake\I18n\Time $publish_start
 * @property \Cake\I18n\Time $publish_end
 */
class ShopCustomerDiscount extends Entity
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
}
