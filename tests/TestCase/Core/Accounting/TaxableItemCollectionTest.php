<?php

namespace Shop\Test\TestCase\Core\Accounting;

use Cake\TestSuite\TestCase;
use Shop\Core\Accounting\TaxableItem;
use Shop\Core\Accounting\TaxableItemCollection;

class TaxableItemCollectionTest extends TestCase
{
    public function testGetName()
    {
        $col = new TaxableItemCollection('items1');
        $this->assertEquals('items1', $col->getName());
    }

    public function testAddAndGetTaxes()
    {
        $col = new TaxableItemCollection('items1');

        $item1 = new TaxableItem('test1', 10);
        $item1->addTax('TAX10', 10);
        $col->addItem($item1);

        $item2 = new TaxableItem('test2', 10);
        $item2->addTax('TAX10', 10);
        $item2->addTax('TAX20', 20);
        $col->addItem($item2);

        $taxes = [
            'TAX10' => [10.0, 20.0, 2.0],
            'TAX20' => [20.0, 10.0, 2.0],
        ];
        $this->assertEquals($taxes, $col->getTaxes());
    }

    public function testAddGetAndRemoveItem()
    {
        $col = new TaxableItemCollection('items1');
        $item1 = new TaxableItem('test1', 10);
        $item2 = new TaxableItem('test2', 10);
        $col->addItem($item1);
        $col->addItem($item2);
        $this->assertEquals(2, count($col->getItems()));
        $this->assertEquals($item1, $col->getItem('test1'));
        $this->assertEquals($item2, $col->getItem('test2'));
        $this->assertNotEquals($item1, $col->getItem('test2'));

        // remove item by name
        $col->removeItem('test2');
        $this->assertEquals(1, count($col->getItems()));
        $this->assertEquals(null, $col->getItem('test2'));

        // remove item by passing the item instance
        $col->removeItem($item1);
        $this->assertEquals(0, count($col->getItems()));
        $this->assertEquals(null, $col->getItem('test1'));
    }

    public function testGetValue()
    {
        $col = new TaxableItemCollection('items1');
        $this->assertEquals(0.0, $col->getValue());

        $item1 = new TaxableItem('test1', 10);
        $item1->addTax('TAX10', 10);
        $col->addItem($item1);
        $this->assertEquals(11.0, $col->getValue());

        $item2 = new TaxableItem('test2', 10);
        $item2->addTax('TAX10', 10);
        $item2->addTax('TAX20', 20);
        $col->addItem($item2);
        $this->assertEquals(24.0, $col->getValue());
    }

    public function testGetBaseValue()
    {
        $col = new TaxableItemCollection('items1');
        $this->assertEquals(0.0, $col->getBaseValue());

        $item1 = new TaxableItem('test1', 10);
        $col->addItem($item1);
        $this->assertEquals(10, $col->getBaseValue());

        $item2 = new TaxableItem('test2', 20);
        $col->addItem($item2);
        $this->assertEquals(30, $col->getBaseValue());
    }

    public function testGetTaxValue()
    {
        $col = new TaxableItemCollection('items1');
        $this->assertEquals(0, $col->getTaxValue());
    }

    public function testGetCurrency()
    {
        //@todo add currency support
        $col = new TaxableItemCollection('items1');
        $this->assertEquals('EUR', $col->getCurrency());
    }
}
