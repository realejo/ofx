<?php
use Realejo\Ofx\Ofx;
use Realejo\Ofx\SignOn;

/**
 * Ofx test case.
 */
class OfxTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Ofx
     */
    private $Ofx;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        // TODO Auto-generated OfxTest::setUp()

        $this->Ofx = new Ofx(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated OfxTest::tearDown()
        $this->Ofx = null;

        parent::tearDown();
    }

    /**
     * Tests Ofx->getHeader()
     */
    public function testGetHeader()
    {
        // TODO Auto-generated OfxTest->testGetHeader()
        $this->markTestIncomplete("getHeader test not implemented");

        $this->Ofx->getHeader(/* parameters */);
    }

    /**
     * Tests Ofx->getHeaders()
     */
    public function testHeaders()
    {
        $headers = array(
                    'um array qualquer',
                    'por que o header não é nomalizado',
                    'deveria ser?',
                    'key'=>'value'
        );

        $this->assertInstanceOf('\Realejo\Ofx\Ofx', $this->Ofx->setHeaders($headers));
        $this->assertEquals($headers, $this->Ofx->getHeaders());
        $this->assertEquals($headers['key'], $this->Ofx->getHeader('key'));
        $this->assertNull($this->Ofx->getHeader('não existo'));
        $this->assertNull($this->Ofx->getHeader(null));
        $this->assertNull($this->Ofx->getHeader(false));
    }

    /**
     * Tests Ofx->getSignOn()
     */
    public function testSettersGetters()
    {
        $this->assertInstanceOf('\Realejo\Ofx\Ofx', $this->Ofx->setSignOn(new SignOn()));
        $this->assertInstanceOf('\Realejo\Ofx\SignOn', $this->Ofx->getSignOn());
    }
}

