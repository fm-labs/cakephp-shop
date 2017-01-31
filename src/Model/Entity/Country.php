<?php
namespace Shop\Model\Entity;

use Cake\ORM\Entity;

/**
 * Country Entity.
 *
 * @property int $id
 * @property string $iso2
 * @property string $iso3
 * @property string $name
 * @property string $name_de
 * @property int $priority
 * @property bool $is_published
 */
class Country extends Entity
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
