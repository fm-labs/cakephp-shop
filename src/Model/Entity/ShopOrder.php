<?php
namespace Shop\Model\Entity;

use Banana\Lib\Status;
use Cake\Core\Configure;
use Cake\I18n\Number;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Shop\Lib\Shop;
use Shop\Lib\Taxation;

/**
 * ShopOrder Entity.
 *
 * @property int $id
 * @property string $uuid
 * @property string $cartid
 * @property string $sessionid
 * @property int $shop_customer_id
 * @property \Shop\Model\Entity\ShopCustomer $shop_customer
 * @property int $nr
 * @property string $title
 * @property float $items_value_net
 * @property float $items_value_tax
 * @property float $items_value_taxed
 * @property string $shipping_type
 * @property float $shipping_value_net
 * @property float $shipping_value_tax
 * @property float $shipping_value_taxed
 * @property float $order_value_total
 * @property string $status
 * @property \Cake\I18n\Time $submitted
 * @property \Cake\I18n\Time $confirmed
 * @property \Cake\I18n\Time $delivered
 * @property \Cake\I18n\Time $invoiced
 * @property \Cake\I18n\Time $payed
 * @property string $customer_notes
 * @property string $staff_notes
 * @property int $billing_address_id
 * @property \Shop\Model\Entity\ShopAddress $billing_address
 * @property string $billing_first_name
 * @property string $billing_last_name
 * @property string $billing_name
 * @property bool $billing_is_company
 * @property string $billing_taxid
 * @property string $billing_zipcode
 * @property string $billing_city
 * @property string $billing_country
 * @property int $shipping_address_id
 * @property \Shop\Model\Entity\ShopAddress $shipping_address
 * @property bool $shipping_use_billing
 * @property string $shipping_first_name
 * @property string $shipping_last_name
 * @property string $shipping_name
 * @property bool $shipping_is_company
 * @property string $shipping_zipcode
 * @property string $shipping_city
 * @property string $shipping_country
 * @property string $customer_phone
 * @property string $customer_email
 * @property string $customer_ip
 * @property string $payment_type
 * @property string $payment_info_1
 * @property string $payment_info_2
 * @property string $payment_info_3
 * @property bool $is_temporary
 * @property bool $is_storno
 * @property bool $is_deleted
 * @property bool $agree_terms
 * @property bool $agree_newsletter
 * @property string $locale
 * @property \Cake\I18n\Time $modified
 * @property \Cake\I18n\Time $created
 * @property \Shop\Model\Entity\ShopOrderItem[] $shop_order_items
 */
class ShopOrder extends Entity
{

    /**
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    protected $_virtual = [
        'nr_formatted',
        'invoice_nr_formatted',
        'is_billing_selected',
        'is_shipping_selected',
        'is_payment_selected',
        //'billing_address_formatted',
        //'selected_address_formatted',
        'currency',
        'base_currency',
        'order_value_tax',
        //'billing_address',
        //'shipping_address',
        'qty', // alias for amount (get/set)
        'cc_brand',
        'cc_number',
        'cc_holder_name',
        'cc_expires_at',
        'is_reverse_charge',

        // formatted values
        'order_value_total_formatted'
    ];

    /**
     * @return ShopOrderAddress
     */
    public function getBillingAddress()
    {
        return $this->billing_address;
    }

    /**
     * @return ShopOrderAddress
     */
    public function getShippingAddress()
    {
        return $this->shipping_address;
    }

    /**
     * @return mixed
     */
    protected function _getQty()
    {
        return $this->amount;
    }

    /**
     * @param $val
     * @return $this
     */
    protected function _setQty($val)
    {
        return $this->set('amount', $val);
    }

    public function getStatus()
    {
        if ($this->status instanceof Status) {
            return $this->status->getStatus();
        }
        return $this->status;
    }

    /**
     * @return ShopCustomer|null
     */
    public function getShopCustomer()
    {
        return TableRegistry::get('Shop.ShopCustomers')
            ->find()
            ->where(['ShopCustomers.id' => $this->shop_customer_id])
            ->first();
    }

    /**
     * @return ShopCustomer|null
     */
    protected function _getShopCustomer()
    {
        if (!isset($this->_properties['shop_customer'])) {
            $this->_properties['shop_customer'] = $this->getShopCustomer();
        }

        return $this->_properties['shop_customer'];
    }

    /**
     * @return int
     */
    public function getOrderItemsCount()
    {
        return (int)TableRegistry::get('Shop.ShopOrderItems')
            ->find('list')
            ->where(['shop_order_id' => $this->id])->count();
    }

    /**
     * @return int
     */
    protected function _getOrderItemsCount()
    {
        if (!isset($this->_properties['order_items_count'])) {
            $this->_properties['order_items_count'] = $this->getOrderItemsCount();
        }

        return (int)$this->_properties['order_items_count'];
    }

    /**
     * @return int
     */
    public function getOrderItemsQty()
    {
        $orderItems = TableRegistry::get('Shop.ShopOrderItems')
            ->find()
            ->where(['shop_order_id' => $this->id])
            ->contain([])
            ->all()
            ->toArray();

        // items value
        $itemsQty = 0;
        array_walk($orderItems, function ($item) use (&$itemsQty) {
            $itemsQty += $item->amount;
        });

        return $itemsQty;
    }

    /**
     * @return int
     */
    protected function _getOrderItemsQty()
    {
        if (!isset($this->_properties['order_items_qty'])) {
            $this->_properties['order_items_qty'] = $this->getOrderItemsQty();
        }

        return (int)$this->_properties['order_items_qty'];
    }

    /**
     * @return bool|null
     */
    public function isReverseCharge()
    {
        if (!$this->getBillingAddress()) {
            return null;
        }
        $taxid = $this->getBillingAddress()->taxid;
        if (!$taxid) {
            return false;
        }

        return Taxation::isReverseCharge($taxid);
    }

    /**
     * @return mixed
     */
    protected function _getIsReverseCharge()
    {
        if (!isset($this->_properties['is_reverse_charge'])) {
            $this->_properties['is_reverse_charge'] = $this->isReverseCharge();
        }

        return $this->_properties['is_reverse_charge'];
    }

    /**
     * @return null|string
     */
    protected function _getNrFormatted()
    {
        if (isset($this->_properties['nr'])) {
            $orderCfg = Shop::config('Order');

            $prefix = $orderCfg['nrPrefix'];
            $suffix = $orderCfg['nrSuffix'];
            $zeroFill = $orderCfg['nrZerofill'];
            $grp = $this->_properties['ordergroup'];
            $nr = $this->_properties['nr'];

            if ($zeroFill > 0) {
                $nrFill = str_repeat("0", $zeroFill) . (string)$nr;
                $nr = substr($nrFill, $zeroFill * -1);
            }

            return $prefix . $grp . $nr . $suffix;
        }

        return null;
    }

    /**
     * @return null|string
     */
    protected function _getInvoiceNrFormatted()
    {
        if (isset($this->_properties['invoice_nr'])) {
            $orderCfg = Shop::config('Invoice');

            $prefix = $orderCfg['nrPrefix'];
            $suffix = $orderCfg['nrSuffix'];
            $zeroFill = $orderCfg['nrZerofill'];
            $grp = $this->_properties['ordergroup'];
            $nr = $this->_properties['invoice_nr'];

            if ($zeroFill > 0) {
                $nrFill = str_repeat("0", $zeroFill) . (string)$nr;
                $nr = substr($nrFill, $zeroFill * -1);
            }

            return $prefix . $grp . $nr . $suffix;
        }

        return null;
    }

    protected function _getOrderValueTotalFormatted()
    {
        $Number = new Number();
        return $Number->currency($this->order_value_total, $this->currency);
    }

    /**
     * Get the currency for this order
     *
     * @return string
     * @todo Implement currency support for orders
     */
    protected function _getCurrency()
    {
        return 'EUR';
    }

    /**
     * Get the base currency for this order
     *
     * @return string
     * @todo Implement currency support for orders
     */
    protected function _getBaseCurrency()
    {
        return 'EUR';
    }

    /**
     * @return string|null
     */
    protected function _getCcBrand()
    {
        if ($this->payment_type == 'credit_card_internal' && $this->payment_info_1) {
            if ($this->payment_info_1 == 'DELETED') {
                return $this->payment_info_1;
            }

            list($brand, $number) = explode(':', $this->payment_info_1);

            return $brand;
        }
    }

    /**
     * @return string|null
     */
    protected function _getCcNumber()
    {
        if ($this->payment_type == 'credit_card_internal' && $this->payment_info_1) {
            if ($this->payment_info_1 == 'DELETED') {
                return $this->payment_info_1;
            }

            list($brand, $number) = explode(':', $this->payment_info_1);

            return $number;
        }
    }

    /**
     * @return string
     */
    protected function _getCcHolderName()
    {
        if ($this->payment_type == 'credit_card_internal') {
            return $this->payment_info_2;
        }
    }

    /**
     * @return string
     */
    protected function _getCcExpiresAt()
    {
        if ($this->payment_type == 'credit_card_internal') {
            return $this->payment_info_3;
        }
    }

    /**
     * @param $val
     * @return mixed
     */
    protected function _setOrderValueTax($val)
    {
        return $val;
    }

    /**
     * @return mixed
     */
    protected function _getOrderValueTax()
    {
        if (!isset($this->_properties['order_value_tax'])) {
            $this->_properties['order_value_tax'] = Taxation::extractTax($this->_properties['order_value_total'], 20.00); //@TODO!!
        }

        return $this->_properties['order_value_tax'];
    }

    protected function _getItemsValueDisplay()
    {
        return (Shop::config('Price.displayNet')) ? $this->items_value_net : $this->items_value_taxed;
    }
}
