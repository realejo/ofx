<?php
use Realejo\Ofx\Banking\Transaction;
use Realejo\Ofx\Banking\TransactionList;

/**
 * TransactionList test case.
 */
class TransactionListTest extends PHPUnit_Framework_TestCase
{

    /**
     * Tests TransactionList->append()
     */
    public function testAppend()
    {
        $transactionList = new TransactionList();
        $transactionList->append(new Transaction());
        $this->assertInstanceOf('Realejo\Ofx\Banking\Transaction', $transactionList[0]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAppendOnlyTransaction()
    {
        $transactionList = new TransactionList();
        $transactionList->append('Não sou transaction');
    }
}

