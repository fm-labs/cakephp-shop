<?php
declare(strict_types=1);

namespace Shop\Model\Entity;

use Cake\ORM\Entity;

/**
 * ShopCoupon Entity
 *
 * @property int $id
 * @property string $code
 * @property string $type
 * @property string $value
 * @property string $valuetype
 * @property int $max_use
 * @property int $max_use_per_customer
 * @property bool $is_published
 * @property \Cake\I18n\FrozenTime|null $valid_from
 * @property \Cake\I18n\FrozenTime|null $valid_until
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 */
class ShopCoupon extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'code' => true,
        'type' => true,
        'value' => true,
        'valuetype' => true,
        'max_use' => true,
        'max_use_per_customer' => true,
        'is_published' => true,
        'valid_from' => true,
        'valid_until' => true,
        'created' => true,
        'modified' => true,
    ];
}
