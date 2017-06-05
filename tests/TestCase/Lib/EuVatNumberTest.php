<?php

namespace Shop\Test\TestCase\Lib;

use PHPUnit\Framework\TestCase;
use Shop\Lib\EuVatNumber;

/**
 * Class EuVatNumberTest
 * @package Shop\Test\TestCase\Lib
 */
class EuVatNumberTest extends TestCase
{

    /**
     * Test getId method
     */
    public function testGetId()
    {
        $id = 'ATU99999999';
        $n = new EuVatNumber($id);
        $this->assertEquals($id, $n->getId());
    }

    /**
     * Test isValid method
     */
    public function testIsValid()
    {
        $id = 'ATU99999999';
        $n = new EuVatNumber($id);
        $this->assertTrue($n->isValid());
    }

    /**
     * Test getCountryCode method
     */
    public function testGetCountryCode()
    {
        $id = 'ATU99999999';
        $n = new EuVatNumber($id);
        $this->assertEquals('AT', $n->getCountryCode());
    }

    /**
     * Test getNumber method
     */
    public function testGetNumber()
    {
        $id = 'ATU99999999';
        $n = new EuVatNumber($id);
        $this->assertEquals('U99999999', $n->getNumber());
    }

    /**
     * Test __toString method
     */
    public function testToString()
    {
        $id = 'ATU99999999';
        $n = new EuVatNumber($id);
        $this->assertEquals($id, (string) $n);
    }

    /**
     * Test static normalize method
     */
    public function testStaticNormalize()
    {
        $expected = 'ATU99999999';
        $result = EuVatNumber::normalize('AT U99999999');
        $this->assertEquals($expected, $result);
        $result = EuVatNumber::normalize('ATU 99.99.99,99');
        $this->assertEquals($expected, $result);
        $result = EuVatNumber::normalize('A T U 9 9 9 9 9 9 9 9');
        $this->assertEquals($expected, $result);
        $result = EuVatNumber::normalize('A.T.U.9.9.9.9.9.9.9.9');
        $this->assertEquals($expected, $result);
        $result = EuVatNumber::normalize('A,T,U,9,9,9,9,9,9,9,9');
        $this->assertEquals($expected, $result);
    }

    /**
     * Test static validate method
     */
    public function testStaticValidate()
    {
        $this->assertTrue(EuVatNumber::validate('ATU99999999'));
        $this->assertTrue(EuVatNumber::validate('DE123456789'));

        $this->markTestIncomplete('Test VATno. for each supported country');
    }
}
