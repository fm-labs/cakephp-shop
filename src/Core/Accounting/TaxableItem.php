<?php

namespace Shop\Core\Accounting;

class TaxableItem implements TaxableItemInterface
{
    /**
     * @var string Item name
     */
    protected string $name;

    /**
     * @var int|float Net value
     */
    protected int|float $baseValue;

    /**
     * @var int|float Calculated tax value
     */
    protected int|float $tax;

    /**
     * @var int|float Calculated taxed value (base value + tax)
     */
    protected int|float $value;

    /**
     * @var array List of taxes applied to this item. [TAXRATE,NETVALUE,TAXVALUE]
     */
    protected array $taxes = [];

    /**
     * @param float $baseValue
     * @param float $taxRate
     * @return float
     */
    public static function calculateTax(float $baseValue, float $taxRate): float
    {
        //@todo precision
        return $baseValue / 100 * $taxRate;
    }

    /**
     * @param string $name
     * @param int|float $baseValue
     */
    public function __construct(string $name, int|float $baseValue)
    {
        $this->name = $name;
        $this->baseValue = $baseValue;
        $this->calculateValues();
    }

    public function setBaseValue(float $baseValue)
    {
        $this->baseValue = $baseValue;

        array_walk($this->taxes, function(&$tax, $taxId) {
            $tax[1] = $this->baseValue;
            $tax[2] = self::calculateTax($this->baseValue, $tax[0]);
        });
        $this->calculateValues();
    }

    /**
     * @param string $taxId
     * @param float $taxRate
     * @return $this
     */
    public function addTax(string $taxId, float $taxRate): static
    {
        $this->taxes[$taxId] = [$taxRate, $this->baseValue, self::calculateTax($this->baseValue, $taxRate)];
        $this->calculateValues();

        return $this;
    }

    /**
     * @return void
     */
    protected function calculateValues(): void
    {
        $this->tax = array_reduce($this->taxes, function ($prev, $cur) {
            return $prev + $cur[2];
        }, 0);

        $this->value = $this->baseValue + $this->tax;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getBaseValue(): float
    {
        return $this->baseValue;
    }

    /**
     * @inheritDoc
     */
    public function getTaxes(): array
    {
        return $this->taxes;
    }

    /**
     * @inheritDoc
     */
    public function getTaxValue(): float
    {
        return $this->tax;
    }

    /**
     * @inheritDoc
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function getCurrency(): string
    {
        return "EUR";
    }
}