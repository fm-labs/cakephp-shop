<?php

namespace Shop\Test\TestCase\Lib;

use PHPUnit\Framework\TestCase;
use Shop\Lib\Taxation;

/**
 * Class TaxationTest
 * @package Shop\Test\TestCase\Lib
 */
class TaxationTest extends TestCase
{
    /**
     * Test static taxrateByVatId method
     */
    public function testStaticTaxrateByVatId()
    {
        $this->assertEquals(0.00, Taxation::taxrateByVatId('DE123456789'));
        $this->assertEquals(20.00, Taxation::taxrateByVatId('ATU99999999'));

        $this->markTestIncomplete('Taxation::taxrateByVatId is incomplete');
    }

    /**
     * Test static isReverseCharge method
     */
    public function testStaticIsReverseCharge()
    {
        $this->assertTrue(Taxation::isReverseCharge('DE123456789', 'AT'));
        $this->assertFalse(Taxation::isReverseCharge('ATU99999999', 'AT'));

        $this->assertFalse(Taxation::isReverseCharge('DE123456789', 'DE'));
        $this->assertTrue(Taxation::isReverseCharge('ATU99999999', 'DE'));
    }

    /**
     * Test static tax method
     */
    public function testStaticTax()
    {
        $this->assertEquals(0, Taxation::tax(0, 0.00));
        $this->assertEquals(0, Taxation::tax(1, 0.00));
        $this->assertEquals(0.1, Taxation::tax(1, 10.00));
        $this->assertEquals(1, Taxation::tax(10, 10.00));
        $this->assertEquals(0.2, Taxation::tax(1, 20.00));
        $this->assertEquals(2, Taxation::tax(10, 20.00));
    }

    /**
     * Test static withTax method
     */
    public function testWithTax()
    {
        $this->assertEquals(0, Taxation::withTax(0, 0.00));
        $this->assertEquals(1, Taxation::withTax(1, 0.00));
        $this->assertEquals(1.1, Taxation::withTax(1, 10.00));
        $this->assertEquals(11, Taxation::withTax(10, 10.00));
        $this->assertEquals(1.2, Taxation::withTax(1, 20.00));
        $this->assertEquals(12, Taxation::withTax(10, 20.00));
    }

    /**
     * Test static withoutTax method
     */
    public function testWithoutTax()
    {
        $this->assertEquals(1, Taxation::withoutTax(1.1, 10.00));
        $this->assertEquals(10, Taxation::withoutTax(11, 10.00));
        $this->assertEquals(1, Taxation::withoutTax(1.2, 20.00));
        $this->assertEquals(10, Taxation::withoutTax(12, 20.00));
    }

    /**
     * Test static extractTax method
     */
    public function testExtractTax()
    {
        $this->assertEquals(0.1, Taxation::extractTax(1.1, 10.00));
        $this->assertEquals(1, Taxation::extractTax(11, 10.00));
        $this->assertEquals(0.2, Taxation::extractTax(1.2, 20.00));
        $this->assertEquals(2, Taxation::extractTax(12, 20.00));
    }

}
