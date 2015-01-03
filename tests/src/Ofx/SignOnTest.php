<?php
use Realejo\Ofx\SignOn;
use Realejo\Ofx\SignOn\Response as SignOnResponse;
use Realejo\Ofx\SignOn\Request as SignOnRequest;

/**
 * SignOn test case.
 */
class SignOnTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var SignOn
     */
    private $SignOn;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        // TODO Auto-generated SignOnTest::setUp()

        $this->SignOn = new SignOn(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated SignOnTest::tearDown()
        $this->SignOn = null;

        parent::tearDown();
    }

    /**
     * Tests SignOn->setRequest()/SignOn->getRequest()
     */
    public function testRequest()
    {
        $this->assertInstanceOf('Realejo\Ofx\SignOn', $this->SignOn->setRequest(new SignOnRequest()));
        $this->assertInstanceOf('Realejo\Ofx\SignOn\Request', $this->SignOn->getRequest());
    }

    /**
     * Tests SignOn->setResponse()/SignOn->getResponse()
     */
    public function testResponse()
    {
        $this->assertInstanceOf('Realejo\Ofx\SignOn', $this->SignOn->setResponse(new SignOnResponse()));
        $this->assertInstanceOf('Realejo\Ofx\SignOn\Response', $this->SignOn->getResponse());
    }

    /**
     * Tests SignOn::parse()
     */
    public function testParseResponse()
    {
        $xml = '<OFX>
                  <SIGNONMSGSRSV1>
                    <SONRS>
                      <STATUS>
                        <CODE>0</CODE>
                        <SEVERITY>INFO</SEVERITY>
                      </STATUS>
                      <DTSERVER>20141230120000[-3:BRT]</DTSERVER>
                      <LANGUAGE>POR</LANGUAGE>
                      <FI>
                        <ORG>Banco do Brasil</ORG>
                          <FID>1</FID>
                      </FI>
                    </SONRS>
                  </SIGNONMSGSRSV1>
                </OFX>';

        $signon = SignOn::parse($xml);
        $this->assertInstanceOf('\Realejo\Ofx\SignOn', $signon);
        $this->assertInstanceOf('\Realejo\Ofx\SignOn\Response', $signon->getResponse());
        $this->assertNull($signon->getRequest());
        $this->assertNotNull($signon->getResponse()->statusCode);
        $this->assertEquals(0, $signon->getResponse()->statusCode);
        $this->assertEquals('INFO', $signon->getResponse()->statusSeverity);
        $this->assertEquals('2014-12-30', $signon->getResponse()->date->format('Y-m-d'));
        $this->assertEquals('POR', $signon->getResponse()->language);
        $this->assertEquals('Banco do Brasil', $signon->getResponse()->fiOrganization);
        $this->assertEquals('1', $signon->getResponse()->fiUniqueId);

        $xml = \Realejo\Ofx\Parser::makeXML($xml);

        // Os mesmos testes acima
        $signon = SignOn::parse($xml);
        $this->assertInstanceOf('\Realejo\Ofx\SignOn', $signon);
        $this->assertInstanceOf('\Realejo\Ofx\SignOn\Response', $signon->getResponse());
        $this->assertNull($signon->getRequest());
        $this->assertNotNull($signon->getResponse()->statusCode);
        $this->assertEquals(0, $signon->getResponse()->statusCode);
        $this->assertEquals('INFO', $signon->getResponse()->statusSeverity);
        $this->assertEquals('2014-12-30', $signon->getResponse()->date->format('Y-m-d'));
        $this->assertEquals('POR', $signon->getResponse()->language);
        $this->assertEquals('Banco do Brasil', $signon->getResponse()->fiOrganization);
        $this->assertEquals('1', $signon->getResponse()->fiUniqueId);

        // Testes com XML invalido
        // SEM OFX
        $xml = '
                  <SIGNONMSGSRSV1>
                    <SONRS>
                      <STATUS>
                        <CODE>0</CODE>
                        <SEVERITY>INFO</SEVERITY>
                      </STATUS>
                      <DTSERVER>20141230120000[-3:BRT]</DTSERVER>
                      <LANGUAGE>POR</LANGUAGE>
                      <FI>
                        <ORG>Banco do Brasil</ORG>
                          <FID>1</FID>
                      </FI>
                    </SONRS>
                  </SIGNONMSGSRSV1>
                ';

        $signon = SignOn::parse($xml);
        $this->assertInstanceOf('\Realejo\Ofx\SignOn', $signon);
        $this->assertInstanceOf('\Realejo\Ofx\SignOn\Response', $signon->getResponse());
        $this->assertNull($signon->getRequest());

        // Testes com XML invalido
        // SEM OFX e SIGNONMSGSRSV1
        $xml = '
                    <SONRS>
                      <STATUS>
                        <CODE>0</CODE>
                        <SEVERITY>INFO</SEVERITY>
                      </STATUS>
                      <DTSERVER>20141230120000[-3:BRT]</DTSERVER>
                      <LANGUAGE>POR</LANGUAGE>
                      <FI>
                        <ORG>Banco do Brasil</ORG>
                          <FID>1</FID>
                      </FI>
                    </SONRS>
                ';

        $signon = SignOn::parse($xml);
        $this->assertInstanceOf('\Realejo\Ofx\SignOn', $signon);
        $this->assertNull($signon->getResponse());
        $this->assertNull($signon->getRequest());

        // Testes com XML invalido
        // SEM SIGNONMSGSRSV1
        $xml = '<OFX>
                    <SONRS>
                      <STATUS>
                        <CODE>0</CODE>
                        <SEVERITY>INFO</SEVERITY>
                      </STATUS>
                      <DTSERVER>20141230120000[-3:BRT]</DTSERVER>
                      <LANGUAGE>POR</LANGUAGE>
                      <FI>
                        <ORG>Banco do Brasil</ORG>
                          <FID>1</FID>
                      </FI>
                    </SONRS>
            </OFX>
                ';

        $signon = SignOn::parse($xml);
        $this->assertInstanceOf('\Realejo\Ofx\SignOn', $signon);
        $this->assertNull($signon->getResponse());
        $this->assertNull($signon->getRequest());
    }
}

