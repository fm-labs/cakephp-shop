<?php
declare(strict_types=1);

namespace Shop\Test\TestCase\Model\Table;

use Cake\TestSuite\TestCase;
use Shop\Model\Table\ShopCouponsTable;

/**
 * Shop\Model\Table\ShopCouponsTable Test Case
 */
class ShopCouponsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Shop\Model\Table\ShopCouponsTable
     */
    protected $ShopCoupons;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'plugin.Shop.ShopCoupons',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ShopCoupons') ? [] : ['className' => ShopCouponsTable::class];
        $this->ShopCoupons = $this->getTableLocator()->get('ShopCoupons', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ShopCoupons);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \Shop\Model\Table\ShopCouponsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \Shop\Model\Table\ShopCouponsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
