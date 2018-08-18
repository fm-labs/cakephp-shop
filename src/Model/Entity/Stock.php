<?php
namespace Shop\Model\Entity;

use Cake\ORM\Entity;

/**
 * Stock Entity.
 *
 * @property int $id
 * @property string $title
 * @property bool $is_default
 * @property \Shop\Model\Entity\ShopStockTransfer[] $shop_stock_transfers
 * @property \Shop\Model\Entity\ShopStockValue[] $shop_stock_values
 */
class Stock extends Entity
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
