<?php

namespace Shop\Core\Accounting;

class TaxableItemCollection implements TaxableItemInterface
{
    /**
     * @var string Item name
     */
    protected string $name;

    /**
     * @var int|float Base Net value
     */

    protected int|float $baseValue;

    /**
     * @var int|float Calculated tax value
     */
    protected int|float $tax;

    /**
     * @var int|float Calculated taxed value (net + tax)
     */
    protected int|float $value;

    /**
     * @var array List of taxes applied to this item. [TAXRATE,NETVALUE,TAXVALUE]
     */
    protected array $taxes = [];

    /**
     * @var array List of TaxableItemInterface objects.
     */
    protected array $items = [];

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->baseValue = 0;
        $this->value = 0;
        $this->tax = 0;
        $this->taxes = [];
        $this->items = [];
    }

    /**
     * @param string $taxId
     * @param float $taxRate
     * @return $this
     */
    public function addItem(TaxableItemInterface $item): static
    {
        $this->items[$item->getName()] = $item;
        $this->calculateValues();

        return $this;
    }

    /**
     * @param TaxableItemInterface|string $item TaxableItemInterface instance or item name
     * @return $this
     */
    public function removeItem(mixed $item): static
    {
        $itemName = $item;
        if ($item instanceof TaxableItemInterface) {
            $itemName = $item->getName();
        }

        if (isset($this->items[$itemName])) {
            unset($this->items[$itemName]);
            $this->calculateValues();
        }

        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getItem(string $name)
    {
        return $this->items[$name] ?? null;
    }

    /**
     * @return void
     */
    protected function calculateValues(): void
    {
        $this->baseValue = array_reduce($this->items, function ($prev, TaxableItemInterface $cur) {
            return $prev + $cur->getBaseValue();
        }, 0);

        $this->value = array_reduce($this->items, function ($prev, TaxableItemInterface $cur) {
            return $prev + $cur->getValue();
        }, 0);

        $this->taxes = array_reduce($this->items, function ($prev, TaxableItemInterface $cur) {
            foreach ($cur->getTaxes() as $taxId => $tax) {
                if (isset($prev[$taxId])) {
                    $_prev = $prev[$taxId];
                    $prev[$taxId] = [
                        $_prev[0], // tax rate
                        $_prev[1] + $tax[1], // base value
                        $_prev[2] + $tax[2], // tax value
                    ];
                } else {
                    $prev[$taxId] = $tax;
                }
            }
            return $prev;
        }, []);
        //debug($this->taxes);

        $this->tax = array_reduce($this->taxes, function ($prev, $cur) {
            return $prev + $cur[2];
        }, 0);
        //debug($this->tax);
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