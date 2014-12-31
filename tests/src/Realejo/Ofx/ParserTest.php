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
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
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
}
