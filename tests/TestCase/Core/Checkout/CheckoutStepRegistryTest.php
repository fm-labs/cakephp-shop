<?php

namespace Shop\Test\TestCase\Core\Checkout;

use Cake\Controller\ComponentRegistry;
use Cake\Controller\Controller;
use Cake\TestSuite\TestCase;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Core\Checkout\CheckoutStepRegistry;
use Zend\Diactoros\ServerRequest;

/**
 * Class CheckoutStepRegistryTest
 *
 * @package Shop\Test\TestCase\Core\Checkout
 */
class CheckoutStepRegistryTest extends TestCase
{
    /**
     * @var CheckoutStepRegistry
     */
    public $steps;

    /**
     * Setup test
     */
    public function setUp()
    {
        parent::setUp();

        $this->request = new ServerRequest('checkout/index');
        $controller = new Controller($this->request);
        $registry = new ComponentRegistry($controller);
        $this->Checkout = new CheckoutComponent($registry, []);
        $this->steps = new CheckoutStepRegistry($this->Checkout);
    }

    /**
     * tearDown
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        unset($this->request);
        unset($this->Checkout);
        unset($this->steps);
    }

    /**
     * Test constructor method
     */
    public function testLoad()
    {
        $result = $this->steps->load('test', [
            'className' => '\\Shop\\Test\\TestCase\\Core\\Checkout\\TestCheckoutStep'
        ]);
        $this->assertInstanceOf('\\Shop\\Core\\Checkout\\CheckoutStepInterface', $result);
    }

    /**
     * Test IteratorInterface implementation
     */
    public function testIteratorInterface()
    {
        $this->steps->load('test', [
            'className' => '\\Shop\\Test\\TestCase\\Core\\Checkout\\TestCheckoutStep'
        ]);
        $this->steps->load('test2', [
            'className' => '\\Shop\\Test\\TestCase\\Core\\Checkout\\TestCheckoutStep'
        ]);
        $this->steps->load('test3', [
            'className' => '\\Shop\\Test\\TestCase\\Core\\Checkout\\TestCheckoutStep'
        ]);

        $this->assertEquals('test', $this->steps->key());
        $this->assertTrue($this->steps->valid());
        $this->assertInstanceOf('\\Shop\\Core\\Checkout\\CheckoutStepInterface', $this->steps->current());

        $this->steps->next();
        $this->assertTrue($this->steps->valid());
        $this->assertEquals('test2', $this->steps->key());
        $this->assertInstanceOf('\\Shop\\Core\\Checkout\\CheckoutStepInterface', $this->steps->current());

        $this->steps->next();
        $this->assertTrue($this->steps->valid());
        $this->assertEquals('test3', $this->steps->key());
        $this->assertInstanceOf('\\Shop\\Core\\Checkout\\CheckoutStepInterface', $this->steps->current());

        $this->steps->next();
        $this->assertFalse($this->steps->valid());
        $this->assertFalse($this->steps->key());
        $this->assertEquals(null, $this->steps->current());
    }

    /**
     * Test SeekableInterface implementation
     */
    public function testSeekableInterface()
    {
        $this->steps->load('test', [
            'className' => '\\Shop\\Test\\TestCase\\Core\\Checkout\\TestCheckoutStep'
        ]);
        $this->steps->load('test2', [
            'className' => '\\Shop\\Test\\TestCase\\Core\\Checkout\\TestCheckoutStep'
        ]);
        $this->steps->load('test3', [
            'className' => '\\Shop\\Test\\TestCase\\Core\\Checkout\\TestCheckoutStep'
        ]);

        $this->steps->seek('test');
        $this->assertTrue($this->steps->valid());
        $this->assertEquals('test', $this->steps->key());

        $this->steps->seek('test3');
        $this->assertTrue($this->steps->valid());
        $this->assertEquals('test3', $this->steps->key());
    }

    /**
     * Test SeekableInterface out-of-bounds
     */
    public function testSeekableInterfaceOutOfBounds()
    {
        $this->steps->load('test', [
            'className' => '\\Shop\\Test\\TestCase\\Core\\Checkout\\TestCheckoutStep'
        ]);
        $this->steps->load('test2', [
            'className' => '\\Shop\\Test\\TestCase\\Core\\Checkout\\TestCheckoutStep'
        ]);
        $this->steps->load('test3', [
            'className' => '\\Shop\\Test\\TestCase\\Core\\Checkout\\TestCheckoutStep'
        ]);

        $this->setExpectedException('\\OutOfBoundsException');
        $this->steps->seek('test4');
    }
}
