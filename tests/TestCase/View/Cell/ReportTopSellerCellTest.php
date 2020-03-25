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
     * @var \Cake\Http\ServerRequest|\PHPUnit\Framework\MockObject\MockObject
     */
    public $request;

    /**
     * Response mock
     *
     * @var \Cake\Http\Response|\PHPUnit\Framework\MockObject\MockObject
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
    public function setUp(): void
    {
        parent::setUp();
        $this->request = $this->getMockBuilder('Cake\Http\ServerRequest')->getMock();
        $this->response = $this->getMockBuilder('Cake\Http\Response')->getMock();
        $this->ReportTopSeller = new ReportTopSellerCell($this->request, $this->response);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
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
