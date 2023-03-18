<?php

namespace Shop\Test\TestCase\Core\Accounting;

use Cake\TestSuite\TestCase;
use Shop\Core\Accounting\TaxableItem;

class TaxableItemTest extends TestCase
{
    public function testStaticCalculateTax()
    {
        $tax = TaxableItem::calculateTax(20, 10);
        $this->assertEquals(2.0, $tax);
    }

    public function testAddAndGetTaxes()
    {
        $item = new TaxableItem('test1', 20);
        $item->addTax('TAX10', 10.0);
        $item->addTax('TAX20', 20.0);

        $taxes = [
            'TAX10' => [10.0, 20.0, 2.0],
            'TAX20' => [20.0, 20.0, 4.0],
        ];
        $this->assertEquals($taxes, $item->getTaxes());
    }

    public function testGetValue()
    {
        $item = new TaxableItem('test1', 20);
        $this->assertEquals(20.0, $item->getValue());
        $item->addTax('TAX10', 10.0);
        $this->assertEquals(22.0, $item->getValue());
        $item->addTax('TAX20', 20.0);
        $this->assertEquals(26.0, $item->getValue());
    }

    public function testSetBaseValue()
    {
        $item = new TaxableItem('test1', 20);
        $item->addTax('TAX10', 10.0);
        $this->assertEquals(22.0, $item->getValue());
        $item->setBaseValue(100);
        $this->assertEquals(100, $item->getBaseValue());
        $this->assertEquals(110, $item->getValue());
    }

    public function testGetName()
    {
        $item = new TaxableItem('test1', 100);
        $this->assertEquals('test1', $item->getName());
    }

    public function testGetBaseValue()
    {
        $item = new TaxableItem('test1', 100);
        $this->assertEquals(100, $item->getBaseValue());
    }

    public function testGetTaxValue()
    {
        $item = new TaxableItem('test1', 100);
        $this->assertEquals(0, $item->getTaxValue());
    }

    public function testGetCurrency()
    {
        //@todo add currency support
        $item = new TaxableItem('test1', 100);
        $this->assertEquals('EUR', $item->getCurrency());
    }
}
