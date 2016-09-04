<?php
namespace Shop\Model\Entity;

use Cake\ORM\Entity;

/**
 * StockTransfer Entity.
 *
 * @property int $id
 * @property int $parent_id
 * @property \Shop\Model\Entity\StockTransfer $parent_stock_transfer
 * @property int $shop_stock_id
 * @property \Shop\Model\Entity\ShopStock $shop_stock
 * @property int $shop_product_id
 * @property \Shop\Model\Entity\ShopProduct $shop_product
 * @property int $op
 * @property int $amount
 * @property \Cake\I18n\Time $date
 * @property string $comment
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \Shop\Model\Entity\StockTransfer[] $child_stock_transfers
 */
class StockTransfer extends Entity
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
