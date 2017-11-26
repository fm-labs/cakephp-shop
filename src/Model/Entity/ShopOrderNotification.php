<?php
namespace Shop\Model\Entity;

use Cake\ORM\Entity;

/**
 * ShopOrderNotification Entity
 *
 * @property int $id
 * @property int $shop_order_id
 * @property string $type
 * @property string $message
 * @property int $order_status
 * @property bool $owner_notified
 * @property bool $customer_notified
 * @property \Cake\I18n\Time $created
 *
 * @property \Shop\Model\Entity\ShopOrder $shop_order
 */
class ShopOrderNotification extends Entity
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
        'id' => false
    ];
}
