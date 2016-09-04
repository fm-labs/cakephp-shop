<?php
namespace Shop\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * ShopOrderItem Entity.
 *
 * @property int $id
 * @property int $shop_order_id
 * @property \Shop\Model\Entity\ShopOrder $shop_order
 * @property string $refscope
 * @property int $refid
 * @property string $title
 * @property int $amount
 * @property string $unit
 * @property float $item_value_net
 * @property float $tax_rate
 * @property float $value_net
 * @property float $value_tax
 * @property float $value_total
 * @property string $options
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class ShopOrderItem extends Entity
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

    protected function _getRef()
    {
        if (!isset($this->_properties['ref'])) {
            $ref = null;
            if (isset($this->_properties['refscope']) && isset($this->_properties['refid'])) {
                $refscope = $this->_properties['refscope'];
                $refid = $this->_properties['refid'];

                $ref = TableRegistry::get($refscope)->get($refid);
            }
            $this->_properties['ref'] = $ref;
        }
        return $this->_properties['ref'];
    }

    protected function _getItemValueTaxed()
    {
        return $this->item_value_net * (1 + $this->tax_rate/100);
    }

    protected function _setAmount($val)
    {
        if ($val < 0) {
            $val = $val * -1;
        }

        return $val;
    }

    public function calculate()
    {
        $this->value_net = $this->item_value_net * $this->amount;
        $this->value_tax = $this->value_net * ($this->tax_rate/100);
        $this->value_total = $this->value_net + $this->value_tax;
    }
}
