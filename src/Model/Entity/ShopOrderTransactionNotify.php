<?php
namespace Shop\Model\Entity;

use Cake\ORM\Entity;

/**
 * ShopOrderTransactionNotify Entity.
 *
 * @property int $id
 * @property int $shop_order_transaction_id
 * @property \Shop\Model\Entity\ShopOrderTransaction $shop_order_transaction
 * @property string $type
 * @property string $engine
 * @property string $request_ip
 * @property string $request_url
 * @property string $request_json
 * @property bool $is_valid
 * @property bool $is_processed
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class ShopOrderTransactionNotify extends Entity
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
