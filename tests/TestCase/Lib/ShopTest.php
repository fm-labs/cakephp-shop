<?php

namespace Shop\Test\TestCase\Lib;

use Cake\Core\Configure;
use PHPUnit\Framework\TestCase;
use Shop\Lib\Shop;

/**
 * Class ShopTest
 *
 * @package Shop\Test\TestCase\Lib
 */
class ShopTest extends TestCase
{
    /**
     * Test static config method
     */
    public function testStaticConfig()
    {
        Configure::write('Shop.Test.foo', 'baz');

        $this->assertEquals('baz', Shop::config('Shop.Test.foo'));
        $this->assertEquals('baz', Shop::config('Test.foo'));
    }
}
