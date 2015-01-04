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

    public function testParseXml()
    {
        $parsed = $this->Parser->makeXML('<TAG>complete</TAG>');
        $this->assertInstanceOf('\SimpleXMLElement', $parsed);
        $this->assertEquals('complete', $parsed->TAG);

        $parsed = $this->Parser->makeXML('<TAG>  complete  </TAG>');
        $this->assertInstanceOf('\SimpleXMLElement', $parsed);
        $this->assertEquals('complete', $parsed->TAG);

        $parsed = $this->Parser->makeXML('  <TAG>complete</TAG>  ');
        $this->assertInstanceOf('\SimpleXMLElement', $parsed);
        $this->assertEquals('complete', $parsed->TAG);

        $parsed = $this->Parser->makeXML('  <TAG>
            complete
            </TAG>  ');
        $this->assertInstanceOf('\SimpleXMLElement', $parsed);
        $this->assertEquals('complete', $parsed->TAG);

        $parsed = $this->Parser->makeXML('<TAG>complete</TAG>');
        $this->assertInstanceOf('\SimpleXMLElement', $parsed);
        $this->assertEquals('complete', $parsed->TAG);

        $parsed = $this->Parser->makeXML('<TAG>incomplete');
        $this->assertInstanceOf('\SimpleXMLElement', $parsed);
        $this->assertEquals('incomplete', $parsed->TAG);
    }

    /**
     * Tests Parser->createFromFile()
     */
    public function testCreateFromFileChecking1()
    {
        $this->assertFalse($this->Parser->createFromFile('não existo'));

        $assetsRoot = realpath(__DIR__.'/../../assets');
        $this->assertNotEmpty($assetsRoot);

        $ofx = $this->Parser->createFromFile($assetsRoot .'/CHECKING-1.OFX');
        $this->assertInstanceOf('Realejo\Ofx\Ofx', $ofx);

        $this->assertNotNull($ofx->getHeaders());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Banking', $ofx->getBanking());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Statement', $ofx->getBanking()->getStatement());
        $this->assertNull($ofx->getBanking()->getStatement()->getRequest());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Statement\Response', $ofx->getBanking()->getStatement()->getResponse());
        $this->assertNull($ofx->getBanking()->getStatement()->getResponse()->getCreditcardAccount());
        $this->assertInstanceOf('Realejo\Ofx\Banking\TransactionList', $ofx->getBanking()->getStatement()->getResponse()->getTransactionList());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Balance', $ofx->getBanking()->getStatement()->getResponse()->getLedgerBalance());
        $this->assertNull($ofx->getBanking()->getStatement()->getResponse()->getAvailableBalance());

        $bankAccount = $ofx->getBanking()->getStatement()->getResponse()->getBankAccount();
        $this->assertInstanceOf('Realejo\Ofx\Banking\BankAccount', $bankAccount);
        $this->assertEquals('001', $bankAccount->bankId);
        $this->assertEquals('64864-3', $bankAccount->accountId);
        $this->assertEquals('CHECKING', $bankAccount->accountType);
        $this->assertNull($bankAccount->branchId);
        $this->assertNull($bankAccount->accountKey);

    }

    /**
     * Tests Parser->createFromFile()
     */
    public function testCreateFromFileChecking2()
    {
        $this->assertFalse($this->Parser->createFromFile('não existo'));

        $assetsRoot = realpath(__DIR__.'/../../assets');
        $this->assertNotEmpty($assetsRoot);

        $ofx = $this->Parser->createFromFile($assetsRoot .'/CHECKING-2.OFX');
        $this->assertInstanceOf('Realejo\Ofx\Ofx', $ofx);

        $this->assertNotNull($ofx->getHeaders());
        $this->assertInstanceOf('Realejo\Ofx\SignOn', $ofx->getSignOn());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Banking', $ofx->getBanking());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Statement', $ofx->getBanking()->getStatement());
        $this->assertNull($ofx->getBanking()->getStatement()->getRequest());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Statement\Response', $ofx->getBanking()->getStatement()->getResponse());
        $this->assertNull($ofx->getBanking()->getStatement()->getResponse()->getCreditcardAccount());
        $this->assertInstanceOf('Realejo\Ofx\Banking\TransactionList', $ofx->getBanking()->getStatement()->getResponse()->getTransactionList());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Balance', $ofx->getBanking()->getStatement()->getResponse()->getLedgerBalance());
        $this->assertNull($ofx->getBanking()->getStatement()->getResponse()->getAvailableBalance());

        $bankAccount = $ofx->getBanking()->getStatement()->getResponse()->getBankAccount();
        $this->assertInstanceOf('Realejo\Ofx\Banking\BankAccount', $bankAccount);
        $this->assertEquals('1', $bankAccount->bankId);
        $this->assertEquals('34676-4', $bankAccount->branchId);
        $this->assertEquals('73833-9', $bankAccount->accountId);
        $this->assertEquals('CHECKING', $bankAccount->accountType);
        $this->assertNull($bankAccount->accountKey);
    }

    /**
     * Tests Parser->createFromFile()
     */
    public function testCreateFromFileSavings1()
    {
        $this->assertFalse($this->Parser->createFromFile('não existo'));

        $assetsRoot = realpath(__DIR__.'/../../assets');
        $this->assertNotEmpty($assetsRoot);

        $ofx = $this->Parser->createFromFile($assetsRoot .'/SAVINGS-1.OFX');
        $this->assertInstanceOf('Realejo\Ofx\Ofx', $ofx);

        $this->assertNotNull($ofx->getHeaders());
        $this->assertInstanceOf('Realejo\Ofx\SignOn', $ofx->getSignOn());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Banking', $ofx->getBanking());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Statement', $ofx->getBanking()->getStatement());
        $this->assertNull($ofx->getBanking()->getStatement()->getRequest());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Statement\Response', $ofx->getBanking()->getStatement()->getResponse());
        $this->assertNull($ofx->getBanking()->getStatement()->getResponse()->getCreditcardAccount());
        $this->assertInstanceOf('Realejo\Ofx\Banking\TransactionList', $ofx->getBanking()->getStatement()->getResponse()->getTransactionList());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Balance', $ofx->getBanking()->getStatement()->getResponse()->getLedgerBalance());
        $this->assertNull($ofx->getBanking()->getStatement()->getResponse()->getAvailableBalance());

        $bankAccount = $ofx->getBanking()->getStatement()->getResponse()->getBankAccount();
        $this->assertInstanceOf('Realejo\Ofx\Banking\BankAccount', $bankAccount);
        $this->assertEquals('1', $bankAccount->bankId);
        $this->assertEquals('2168-4', $bankAccount->branchId);
        $this->assertEquals('21684-9/51', $bankAccount->accountId);
        $this->assertEquals('SAVINGS', $bankAccount->accountType);
        $this->assertNull($bankAccount->accountKey);
    }

    /**
     * Tests Parser->createFromFile()
     */
    public function testCreateFromFileCreditcard1()
    {
        $this->assertFalse($this->Parser->createFromFile('não existo'));

        $assetsRoot = realpath(__DIR__.'/../../assets');
        $this->assertNotEmpty($assetsRoot);

        $ofx = $this->Parser->createFromFile($assetsRoot .'/CREDITCARD-1.OFX');
        $this->assertInstanceOf('Realejo\Ofx\Ofx', $ofx);

        $this->assertNotNull($ofx->getHeaders());
        $this->assertInstanceOf('Realejo\Ofx\SignOn', $ofx->getSignOn());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Banking', $ofx->getBanking());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Statement', $ofx->getBanking()->getStatement());
        $this->assertNull($ofx->getBanking()->getStatement()->getRequest());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Statement\Response', $ofx->getBanking()->getStatement()->getResponse());
        $this->assertNull($ofx->getBanking()->getStatement()->getResponse()->getBankAccount());
        $this->assertInstanceOf('Realejo\Ofx\Banking\TransactionList', $ofx->getBanking()->getStatement()->getResponse()->getTransactionList());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Balance', $ofx->getBanking()->getStatement()->getResponse()->getLedgerBalance());
        $this->assertNull($ofx->getBanking()->getStatement()->getResponse()->getAvailableBalance());

        $creditcardAccount = $ofx->getBanking()->getStatement()->getResponse()->getCreditcardAccount();
        $this->assertInstanceOf('Realejo\Ofx\Banking\CreditcardAccount', $creditcardAccount);
        $this->assertEquals('9753648514651548', $creditcardAccount->accountId);
        $this->assertNull($creditcardAccount->accountKey);
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
