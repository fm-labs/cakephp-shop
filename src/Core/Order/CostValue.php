<?php

namespace Shop\Core\Order;

use Shop\Lib\Taxation;

class CostValue implements CostValueInterface
{
    protected $_net;

    protected $_taxrate;

    protected $_label;

    public function __construct($net, $taxrate, $label)
    {
        if (!is_numeric($net)) {
            throw new \InvalidArgumentException("Net must be numeric");
        }
        $this->_net = (double)$net;

        if (!is_numeric($taxrate) || $taxrate < 0 || $taxrate > 100) {
            throw new \InvalidArgumentException("Taxrate MUST be a value between 0 and 100: Given $taxrate");
        }
        $this->_taxrate = (double)$taxrate;

        $this->_label = (string)$label;
    }

    public function getNetValue()
    {
        return $this->_net;
    }

    public function getTaxRate()
    {
        return $this->_taxrate;
    }

    public function getTaxValue()
    {
        return Taxation::tax($this->_net, $this->_taxrate);
    }

    public function getTotalValue()
    {
        return Taxation::withTax($this->_net, $this->_taxrate);
    }

    public function getLabel()
    {
        return $this->_label;
    }

    public function getTaxes()
    {
        return [0 => ['taxRate' => $this->getTaxRate(), 'value' => $this->getTaxValue()]];
    }

    public function toArray()
    {
        return [
            'net' => $this->_net,
            'taxrate' => $this->_taxrate,
            'tax' => $this->getTaxValue(),
            'total' => $this->getTotalValue(),
            'label' => $this->getLabel()
        ];
    }
}
