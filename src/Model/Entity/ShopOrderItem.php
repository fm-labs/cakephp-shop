<?php
namespace Shop\Model\Entity;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Shop\Core\Product\ShopProductInterface;

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

    protected $_virtual = [
        'product',
        'title',
        'sku',
        'currency',
        'base_currency',
        'value_net'
    ];

    /**
     * @var ShopProductInterface
     */
    protected $_product;

    public function requiresShipping()
    {
        return ($this->type === 'virtual') ? false : true;
    }

    /**
     * @return bool
     */
    public function isProcessed()
    {
        return (bool) $this->is_processed;
    }

    /**
     * @return EntityInterface
     */
    protected function _getRef()
    {
        if (!isset($this->_properties['ref'])) {
            $ref = null;
            if (isset($this->_properties['refscope']) && isset($this->_properties['refid'])) {
                $refscope = $this->_properties['refscope'];
                $refid = $this->_properties['refid'];

                $ref = TableRegistry::get($refscope)->find('product')->where(['id' => $refid])->first();
            }
            $this->_properties['ref'] = $ref;
        }
        return $this->_properties['ref'];
    }

    protected function _getSku()
    {
        return ($this->getProduct()) ? $this->getProduct()->getSku() : null;
    }

    protected function _getTitle()
    {
        if (!isset($this->_properties['title'])) {
            $this->_properties['title'] = ($this->getProduct()) ? $this->getProduct()->getTitle() : null;
        }
        return $this->_properties['title'];
    }

    /**
     * Get the currency for this order
     *
     * @return string
     * @todo Implement currency support for orders
     */
    protected function _getCurrency()
    {
        //@TODO Implement shop order item currencies
        return 'EUR';
    }

    /**
     * Get the currency for this order
     *
     * @return string
     * @todo Implement currency support for orders
     */
    protected function _getBaseCurrency()
    {
        return 'EUR';
    }

    /**
     * @return float
     */
    protected function _getItemValueTaxed()
    {
        return $this->item_value_net * (1 + $this->tax_rate/100);
    }

    /**
     * Total net value
     *
     * @return float
    protected function _getValueTotal()
    {
        return $this->item_value_net * $this->amount;
    }
*/

    /**
     * Total gros value
     *
     * @return float
    protected function _getValueTotalTaxed()
    {
        return $this->item_value_net * $this->amount * (1 + $this->tax_rate/100);
    }
*/


    /**
     * @param $val
     * @return float
    protected function _setAmount($val)
    {
        if ($val < 0) {
            $val = $val * -1;
        }

        return $val;
    }
     */

    /**
     * @return ShopProductInterface
     */
    public function getProduct()
    {
        $this->_getRef();
        if (!$this->_properties['ref']) {
            //throw new \RuntimeException(sprintf('ShopOrderItem: Referenced product item not loaded'));
        }

        if (!($this->_properties['ref'] instanceof ShopProductInterface)) {
            //throw new \RuntimeException(sprintf('ShopOrderItem: %s is not an instance of ShopProductInterface',
            //    get_class($this->_properties['ref'])));
        }
        return $this->_properties['ref'];
    }

    /**
     * Calculate totals
     * @return void
     */
    public function calculate()
    {
        $this->value_net = $this->item_value_net * $this->amount;
        $this->value_tax = $this->value_net * ($this->tax_rate/100);
        $this->value_total = $this->value_net + $this->value_tax;
    }
}
