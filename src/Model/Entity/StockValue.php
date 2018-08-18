<?php
namespace Shop\Model\Entity;

use Cake\ORM\Entity;

/**
 * StockValue Entity.
 *
 * @property int $id
 * @property int $shop_stock_id
 * @property \Shop\Model\Entity\ShopStock $shop_stock
 * @property int $shop_product_id
 * @property \Shop\Model\Entity\ShopProduct $shop_product
 * @property int $value
 * @property \Cake\I18n\Time $last_transfer_in
 * @property \Cake\I18n\Time $last_transfer_out
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class StockValue extends Entity
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
