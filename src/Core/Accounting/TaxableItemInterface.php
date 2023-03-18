<?php

namespace Shop\Core\Accounting;

interface TaxableItemInterface
{
    public function getName(): string;

    public function getBaseValue(): float;

    public function getTaxes(): array;

    public function getTaxValue(): float;

    public function getValue(): float;

    public function getCurrency(): string;
}