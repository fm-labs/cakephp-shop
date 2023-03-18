<?php
declare(strict_types=1);

namespace Shop\Core\Order;

class CostCalculator implements CostValueInterface
{
    /**
     * @var array
     */
    protected array $_values = [];

    /**
     * @param $name string
     * @param $net double|CostValueInterface
     * @param $taxrate double|null
     * @param $label double|null
     * @return $this
     */
    public function addValue($name, $net, $taxrate, $label)
    {
        if ($net instanceof CostValueInterface) {
            $val = $net;
        } else {
             $val = new CostValue($net, $taxrate, $label);
        }
        $this->_values[$name] = $val;

        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->_values;
    }

    /**
     * @param $name
     * @return \Shop\Core\Order\CostValueInterface|null
     */
    public function getValue($name)
    {
        return $this->_values[$name] ?? null;
    }

    /**
     * @return array
     */
    public function getTaxes()
    {
        $taxes = [];
        array_walk($this->_values, function (CostValueInterface $val) use (&$taxes) {
            foreach ($val->getTaxes() as $tax) {
                $taxRate = $tax['taxRate'];
                $key = abs($taxRate * 100);
                if (!isset($taxes[$key])) {
                    $taxes[$key] = ['taxRate' => $taxRate, 'value' => 0];
                }
                $taxes[$key]['value'] += $tax['value'];
            }
        });

        return $taxes;
    }

    /**
     * @return double
     */
    public function getNetValue()
    {
        $value = 0;
        array_walk($this->_values, function (CostValueInterface $val) use (&$value) {
            $value += $val->getNetValue();
        });

        return $value;
    }

    /**
     * @return double
     */
    public function getTaxValue()
    {
        $value = 0;
        array_walk($this->_values, function (CostValueInterface $val) use (&$value) {
            $value += $val->getTaxValue();
        });

        return $value;
    }

    /**
     * @return double
     */
    public function getTotalValue()
    {
        $value = 0;
        array_walk($this->_values, function (CostValueInterface $val) use (&$value) {
            $value += $val->getTotalValue();
        });

        return $value;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'values' => $this->_values,
            'net' => $this->getNetValue(),
            'tax' => $this->getTaxValue(),
            'total' => $this->getTotalValue(),
            'taxes' => $this->getTaxes(),
        ];
    }
}
