<?php
use Realejo\Ofx\Banking;
use Realejo\Ofx\Banking\BankStatement;

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
        $this->assertInstanceOf('\Realejo\Ofx\Banking', $this->Banking->setBankStatement(new BankStatement()));
        $this->assertInstanceOf('\Realejo\Ofx\Banking\BankStatement', $this->Banking->getBankStatement());
    }

    /**
     * Tests Banking->parse()
     */
    public function testParse()
    {
        $banking = '<BANKMSGSRSV1>
                        <STMTTRNRS>
                         <TRNUID>1</TRNUID>
                         <STATUS>
                            <CODE>0</CODE>
                            <SEVERITY>INFO</SEVERITY>
                         </STATUS>
                         <STMTRS>
                            <CURDEF>BRL</CURDEF>
                            <BANKACCTFROM>
                               <BANKID>1</BANKID>
                               <BRANCHID>9712-5</BRANCHID>
                               <ACCTID>6666-6</ACCTID>
                               <ACCTTYPE>CHECKING</ACCTTYPE>
                            </BANKACCTFROM>
                            <BANKTRANLIST>
                               <DTSTART>20141031120000[-3:BRT]</DTSTART>
                               <DTEND>20141130120000[-3:BRT]</DTEND>
                               <STMTTRN>
                                  <TRNTYPE>OTHER</TRNTYPE>
                                  <DTPOSTED>20141103120000[-3:BRT]</DTPOSTED>
                                  <TRNAMT>1234.45</TRNAMT>
                                  <FITID>2014110305000000</FITID>
                                  <CHECKNUM>855000028594</CHECKNUM>
                                  <REFNUM>661.855.000.028.594</REFNUM>
                                  <MEMO>BLA BLA BLA</MEMO>
                               </STMTTRN>
                               <STMTTRN>
                                  <TRNTYPE>OTHER</TRNTYPE>
                                  <DTPOSTED>20141103120000[-3:BRT]</DTPOSTED>
                                  <TRNAMT>-123.45</TRNAMT>
                                  <FITID>20141103198500</FITID>
                                  <CHECKNUM>000000157468</CHECKNUM>
                                  <REFNUM>157.468</REFNUM>
                                  <MEMO>Bla bla bla</MEMO>
                               </STMTTRN>
                               <STMTTRN>
                                  <TRNTYPE>OTHER</TRNTYPE>
                                  <DTPOSTED>20141103120000[-3:BRT]</DTPOSTED>
                                  <TRNAMT>-98.76</TRNAMT>
                                  <FITID>20141103155540</FITID>
                                  <CHECKNUM>000000277909</CHECKNUM>
                                  <REFNUM>277.909</REFNUM>
                                  <MEMO>bla bla</MEMO>
                               </STMTTRN>
                               <STMTTRN>
                                  <TRNTYPE>OTHER</TRNTYPE>
                                  <DTPOSTED>20141103120000[-3:BRT]</DTPOSTED>
                                  <TRNAMT>-65.43</TRNAMT>
                                  <FITID>20141103124540</FITID>
                                  <CHECKNUM>000000347879</CHECKNUM>
                                  <REFNUM>347.879</REFNUM>
                                  <MEMO>Bla bla bla</MEMO>
                               </STMTTRN>
                               <STMTTRN>
                                  <TRNTYPE>OTHER</TRNTYPE>
                                  <DTPOSTED>20141103120000[-3:BRT]</DTPOSTED>
                                  <TRNAMT>456.78</TRNAMT>
                                  <FITID>20141103117590</FITID>
                                  <CHECKNUM>000000651888</CHECKNUM>
                                  <REFNUM>651.888</REFNUM>
                                  <MEMO>Bla Bla Bla</MEMO>
                               </STMTTRN>
                            </BANKTRANLIST>
                            <LEDGERBAL>
                               <BALAMT>123456.78</BALAMT>
                               <DTASOF>20141130120000[-3:BRT]</DTASOF>
                            </LEDGERBAL>
                         </STMTRS>
                      </STMTTRNRS>
                    </BANKMSGSRSV1>';

        $banking = $this->Banking->parse($banking);
        $this->assertInstanceOf('Realejo\Ofx\Banking', $banking);
        $this->assertInstanceOf('Realejo\Ofx\Banking\BankStatement', $banking->getBankStatement());

        $response = $banking->getBankStatement()->getResponse();
        $this->assertInstanceOf('Realejo\Ofx\Banking\BankStatement\Response', $response);
        $this->assertEquals('BRL', $response->currency);


        $bankAccount =  $response->getBankAccount();
        $this->assertInstanceOf('Realejo\Ofx\Banking\BankAccount', $bankAccount);
        $this->assertEquals('1', $bankAccount->bankId);
        $this->assertEquals('9712-5', $bankAccount->branchId);
        $this->assertEquals('6666-6', $bankAccount->accountId);
        $this->assertEquals('CHECKING', $bankAccount->accountType);
        $this->assertEquals($bankAccount::TYPE_CHECKING, $bankAccount->accountType);
        $this->assertNull($bankAccount->accountKey);

        $transactionList = $response->getTransactionList();
        $this->assertInstanceOf('Realejo\Ofx\Banking\TransactionList', $transactionList);
        $this->assertEquals('2014-10-31', $transactionList->dateStart->format('Y-m-d'));
        $this->assertEquals('2014-11-30', $transactionList->dateEnd->format('Y-m-d'));

        $this->assertCount(5, $transactionList);

        $transaction = $transactionList[0];
        $this->assertEquals('OTHER', $transaction->type);
        $this->assertEquals('2014-11-03', $transaction->datePosted->format('Y-m-d'));
        $this->assertEquals('1234.45', $transaction->amount);
        $this->assertEquals('2014110305000000', $transaction->fitId);
        $this->assertEquals('855000028594', $transaction->checkNumber);
        $this->assertEquals('661.855.000.028.594', $transaction->referenceNumber);
        $this->assertEquals('BLA BLA BLA', $transaction->memo);

        $transaction = $transactionList[4];
        $this->assertEquals('OTHER', $transaction->type);
        $this->assertEquals('2014-11-03', $transaction->datePosted->format('Y-m-d'));
        $this->assertEquals('456.78', $transaction->amount);
        $this->assertEquals('20141103117590', $transaction->fitId);
        $this->assertEquals('000000651888', $transaction->checkNumber);
        $this->assertEquals('651.888', $transaction->referenceNumber);
        $this->assertEquals('Bla Bla Bla', $transaction->memo);
    }
}

