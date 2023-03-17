<?php
declare(strict_types=1);

namespace Shop\Test\TestCase\Model\Table;

use Cake\TestSuite\TestCase;
use Shop\Model\Table\ShopI18nTable;

/**
 * Shop\Model\Table\ShopI18nTable Test Case
 */
class ShopI18nTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Shop\Model\Table\ShopI18nTable
     */
    protected $ShopI18n;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'plugin.Shop.ShopI18n',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ShopI18n') ? [] : ['className' => ShopI18nTable::class];
        $this->ShopI18n = $this->getTableLocator()->get('ShopI18n', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ShopI18n);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \Shop\Model\Table\ShopI18nTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \Shop\Model\Table\ShopI18nTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
