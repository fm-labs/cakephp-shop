<?php
namespace Shop\Test\TestCase\View\Cell;

use Cake\TestSuite\TestCase;
use Shop\View\Cell\ShopCategoriesCell;

/**
 * Shop\View\Cell\ShopCategoriesCell Test Case
 */
class ShopCategoriesCellTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = $this->getMock('Cake\Network\Request');
        $this->response = $this->getMock('Cake\Network\Response');
        $this->ShopCategoriesCell = new ShopCategoriesCell($this->request, $this->response);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ShopCategoriesCell);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
