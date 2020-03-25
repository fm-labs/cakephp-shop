<?php
declare(strict_types=1);

namespace Shop\Model\Entity;

use Cake\ORM\Entity;

/**
 * ShopOrderTransaction Entity.
 *
 * @property int $id
 * @property int $shop_order_id
 * @property \Shop\Model\Entity\ShopOrder $shop_order
 * @property string $type
 * @property string $engine
 * @property string $currency_code
 * @property float $value
 * @property int $status
 * @property string $ext_txnid
 * @property string $ext_status
 * @property string $init_response
 * @property string $init_request
 * @property string $redirect_url
 * @property string $custom1
 * @property string $custom2
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class ShopOrderTransaction extends Entity
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
