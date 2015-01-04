<?php
use Realejo\Ofx\Banking\Banking;
use Realejo\Ofx\Banking\Statement;

/**
 * Banking test case.
 */
class BankingTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Banking
     */
    private $Banking;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        // TODO Auto-generated BankingTest::setUp()

        $this->Banking = new Banking(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated BankingTest::tearDown()
        $this->Banking = null;

        parent::tearDown();
    }

    /**
     * Tests Getters ans Setters
     */
    public function testSettersGetters()
    {
        $this->assertInstanceOf('\Realejo\Ofx\Banking\Banking', $this->Banking->setStatement(new Statement()));
        $this->assertInstanceOf('\Realejo\Ofx\Banking\Statement', $this->Banking->getStatement());
    }
}

