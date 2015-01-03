<?php
use Realejo\Ofx\Parser;

/**
 * Parser test case.
 */
class ParserTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Parser
     */
    private $Parser;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        // TODO Auto-generated ParserTest::setUp()

        $this->Parser = new Parser(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated ParserTest::tearDown()
        $this->Parser = null;

        parent::tearDown();
    }

    /**
     * Tests Parser->createFromFile()
     */
    public function testCreateFromFile()
    {
        // TODO Auto-generated ParserTest->testCreateFromFile()
        $this->markTestIncomplete("createFromFile test not implemented");

        $this->Parser->createFromFile(/* parameters */);
    }

    /**
     * Tests Parser->createFromString()
     */
    public function testCreateFromString()
    {
        // TODO Auto-generated ParserTest->testCreateFromString()
        $this->markTestIncomplete("createFromString test not implemented");

        $this->Parser->createFromString(/* parameters */);
    }

    public function testHeaders()
    {
        $headers = $this->Parser->parseHeaders('
                OFXHEADER:100
                DATA:OFXSGML
                VERSION : 102
                SECURITY:NONE
                ENCODING:     USASCII
                CHARSET:1252
                COMPRESSION:NONE
                OLDFILEUID:NONE
                NEWFILEUID:NONE
            ');

        $this->assertNotNull($headers, 'headers definidos');
        $this->assertInternalType('array', $headers, 'headers são um array');
        $this->assertArrayHasKey('VERSION',$headers, 'chave VERSION existe');
        $this->assertEquals('102', $headers['VERSION'], 'campo VERSION correto');
        $this->assertEquals('USASCII', $headers['ENCODING'], 'campo ENCODING correto');


        $ofx = $this->Parser->createFromString('
                OFXHEADER:100
                DATA:OFXSGML
                VERSION : 102
                SECURITY:NONE
                ENCODING:     USASCII
                CHARSET:1252
                COMPRESSION:NONE
                OLDFILEUID:NONE
                NEWFILEUID:NONE
            ');

        $this->assertInstanceOf('\Realejo\Ofx\Ofx', $ofx , 'Aruivo OFX criado');
        $headers = $ofx->getHeaders();

        // São os mesmos testes acima
        $this->assertNotNull($headers, 'headers definidos');
        $this->assertInternalType('array', $headers, 'headers são um array');
        $this->assertArrayHasKey('VERSION',$headers, 'chave VERSION existe');
        $this->assertEquals('102', $headers['VERSION'], 'campo VERSION correto');
        $this->assertEquals('USASCII', $headers['ENCODING'], 'campo ENCODING correto');

        // Os mesmos testes usando a chave direta
        $this->assertEquals('102', $ofx->getHeader('VERSION'), 'campo VERSION correto');
        $this->assertEquals('USASCII', $ofx->getHeader('ENCODING'), 'campo ENCODING correto');

    }

    public function testParseDate()
    {
        $this->assertNull($this->Parser->parseDate(null));
        $this->assertNull($this->Parser->parseDate(''));

        $this->assertInstanceOf('DateTime', $this->Parser->parseDate('20141031120000[-3:BRT]'));

        $this->assertEquals('2014-10-31', $this->Parser->parseDate('20141031120000[-3:BRT]')->format('Y-m-d'));
        $this->assertEquals('2014-10-31', $this->Parser->parseDate('20141031120000')->format('Y-m-d'));
        $this->assertEquals('2014-10-31', $this->Parser->parseDate('20141031[-3:BRT]')->format('Y-m-d'));
        $this->assertEquals('2014-10-31', $this->Parser->parseDate('20141031')->format('Y-m-d'));

        $this->assertEquals('12:00:00', $this->Parser->parseDate('20141031120000[-3:BRT]')->format('h:i:s'));
        $this->assertEquals('12:00:00', $this->Parser->parseDate('20141031120000[-3:BRT]')->format('H:i:s'));
        $this->assertEquals('12:00:00', $this->Parser->parseDate('20141031120000')->format('h:i:s'));
        $this->assertEquals('12:00:00', $this->Parser->parseDate('20141031120000')->format('H:i:s'));

        $this->assertEquals('12:00:00', $this->Parser->parseDate('20141031120000.1234[-3:BRT]')->format('H:i:s'));
        $this->assertEquals('12:00:00', $this->Parser->parseDate('20141031120000.1234')->format('H:i:s'));
    }
}
