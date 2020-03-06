<?php
namespace Shop\Test\TestCase\View\Cell;

use Cake\TestSuite\TestCase;
use Shop\View\Cell\ReportTopSellerCell;

/**
 * Shop\View\Cell\ReportTopSellerCell Test Case
 */
class ReportTopSellerCellTest extends TestCase
{

    /**
     * Request mock
     *
     * @var \Cake\Http\ServerRequest|\PHPUnit_Framework_MockObject_MockObject
     */
    public $request;

    /**
     * Response mock
     *
     * @var \Cake\Http\Response|\PHPUnit_Framework_MockObject_MockObject
     */
    public $response;

    /**
     * Test subject
     *
     * @var \Shop\View\Cell\ReportTopSellerCell
     */
    public $ReportTopSeller;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = $this->getMockBuilder('Cake\Http\Request')->getMock();
        $this->response = $this->getMockBuilder('Cake\Http\Response')->getMock();
        $this->ReportTopSeller = new ReportTopSellerCell($this->request, $this->response);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReportTopSeller);

        parent::tearDown();
    }

    /**
     * Test display method
     *
     * @return void
     */
    public function testDisplay()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
