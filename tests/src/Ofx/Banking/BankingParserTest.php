<?php
use Realejo\Ofx\Banking\Parser;

/**
 * Parser test case.
 */
class BankingParserTest extends PHPUnit_Framework_TestCase
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

        // TODO Auto-generated BankingParserTest::setUp()

        $this->Parser = new Parser(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated BankingParserTest::tearDown()
        $this->Parser = null;

        parent::tearDown();
    }

    public function testParseCredircardAccount()
    {
        $content = '<CCACCTFROM><ACCTID>9753648514651548</ACCTID></CCACCTFROM>';
        $account = $this->Parser->parseCreditcardAccount($content);

        $this->assertInstanceOf('Realejo\Ofx\Banking\CreditcardAccount', $account);
        $this->assertEquals('9753648514651548', $account->accountId);
        $this->assertNull($account->accountKey);

        $content = '<CCACCTFROM><ACCTID>
9753648514651548
</ACCTID></CCACCTFROM>';
        $account = $this->Parser->parseCreditcardAccount($content);

        $this->assertInstanceOf('Realejo\Ofx\Banking\CreditcardAccount', $account);
        $this->assertEquals('9753648514651548', $account->accountId);
        $this->assertNull($account->accountKey);
    }

    /**
     * Tests Parser->parse()
     */
    public function testBankResponseParse()
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

        $banking = $this->Parser->parse($banking);
        $this->assertInstanceOf('Realejo\Ofx\Banking\Banking', $banking);
        $this->assertInstanceOf('Realejo\Ofx\Banking\Statement', $banking->getStatement());

        $response = $banking->getStatement()->getResponse();
        $this->assertInstanceOf('Realejo\Ofx\Banking\Statement\Response', $response);
        $this->assertEquals('BRL', $response->currency);

        $this->assertNull($response->getCreditcardAccount(), 'não é cartão de crédito');

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

    /**
     * Tests Parser->parse()
     */
    public function testCreditcardResponseParse()
    {
        $creditcard = '
<CREDITCARDMSGSRSV1>
<CCSTMTTRNRS>
<TRNUID>1</TRNUID>
<STATUS>
<CODE>0</CODE>
<SEVERITY>INFO</SEVERITY>
</STATUS>
<CCSTMTRS>
<CURDEF>BRL</CURDEF>
<CCACCTFROM><ACCTID>
9753648514651548
</ACCTID></CCACCTFROM>
<BANKTRANLIST>
<DTSTART>20140307</DTSTART>
<DTEND>20141110</DTEND>
<STMTTRN>
<TRNTYPE>PAYMENT</TRNTYPE>
<DTPOSTED>20141027</DTPOSTED>
<TRNAMT>1234.56</TRNAMT>
<FITID>2014102749845371082027410000000000</FITID>
<MEMO>PGTO DEBITO CONTA 666 000007108  200</MEMO>
</STMTTRN>
<STMTTRN>
<TRNTYPE>PAYMENT</TRNTYPE>
<DTPOSTED>20141017</DTPOSTED>
<TRNAMT>54.50</TRNAMT>
<FITID>2014101749845371082027410000000001</FITID>
<MEMO>Bla bla bla</MEMO>
</STMTTRN>
<STMTTRN>
<TRNTYPE>PAYMENT</TRNTYPE>
<DTPOSTED>20141019</DTPOSTED>
<TRNAMT>-89.80</TRNAMT>
<FITID>2014101949845371082027410000000002</FITID>
<MEMO>Outro Bla bla bla</MEMO>
</STMTTRN>
<STMTTRN>
<TRNTYPE>PAYMENT</TRNTYPE>
<DTPOSTED>20141026</DTPOSTED>
<TRNAMT>-34.30</TRNAMT>
<FITID>2014102649845371082027410000000008</FITID>
<MEMO>Mais um bla bla bla</MEMO>
</STMTTRN>
<STMTTRN>
<TRNTYPE>PAYMENT</TRNTYPE>
<DTPOSTED>20141025</DTPOSTED>
<TRNAMT>-55.91</TRNAMT>
<FITID>2014102549845371082027410000000013</FITID>
<MEMO>E mais um bla bla bla</MEMO>
</STMTTRN>
</BANKTRANLIST>
<LEDGERBAL>
<BALAMT>-1225.47</BALAMT>
<DTASOF>20141125</DTASOF>
</LEDGERBAL>
</CCSTMTRS>
</CCSTMTTRNRS>
</CREDITCARDMSGSRSV1>
';

        $creditcard = $this->Parser->parse($creditcard);
        $this->assertInstanceOf('Realejo\Ofx\Banking\Banking', $creditcard);
        $this->assertInstanceOf('Realejo\Ofx\Banking\Statement', $creditcard->getStatement());

        $response = $creditcard->getStatement()->getResponse();
        $this->assertInstanceOf('Realejo\Ofx\Banking\Statement\Response', $response);
        $this->assertEquals('BRL', $response->currency);

        $this->assertNull($response->getBankAccount(), 'não é banco');

        $creditcardAccount =  $response->getCreditcardAccount();
        $this->assertInstanceOf('Realejo\Ofx\Banking\CreditcardAccount', $creditcardAccount);
        $this->assertEquals('9753648514651548', $creditcardAccount->accountId);
        $this->assertNull($creditcardAccount->accountKey);

        $transactionList = $response->getTransactionList();
        $this->assertInstanceOf('Realejo\Ofx\Banking\TransactionList', $transactionList);
        $this->assertEquals('2014-03-07', $transactionList->dateStart->format('Y-m-d'));
        $this->assertEquals('2014-11-10', $transactionList->dateEnd->format('Y-m-d'));

        $this->assertCount(5, $transactionList);

        $transaction = $transactionList[0];
        $this->assertEquals('PAYMENT', $transaction->type);
        $this->assertEquals('2014-10-27', $transaction->datePosted->format('Y-m-d'));
        $this->assertEquals(1234.56, $transaction->amount);
        $this->assertEquals('2014102749845371082027410000000000', $transaction->fitId);
        $this->assertNull($transaction->checkNumber);
        $this->assertNull($transaction->referenceNumber);
        $this->assertEquals('PGTO DEBITO CONTA 666 000007108  200', $transaction->memo);

        $transaction = $transactionList[4];
        $this->assertEquals('PAYMENT', $transaction->type);
        $this->assertEquals('2014-10-25', $transaction->datePosted->format('Y-m-d'));
        $this->assertEquals(-55.91, $transaction->amount);
        $this->assertEquals('2014102549845371082027410000000013', $transaction->fitId);
        $this->assertNull($transaction->checkNumber);
        $this->assertNull($transaction->referenceNumber);
        $this->assertEquals('E mais um bla bla bla', $transaction->memo);
    }

}

