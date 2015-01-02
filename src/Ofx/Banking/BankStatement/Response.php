<?php
namespace Realejo\Ofx\Banking\BankStatement;

use Realejo\Ofx\Banking\BankAccount;
use Realejo\Ofx\Banking\TransactionList;

class Response
{

    public $statusCode;

    public $statusSeverity;

    /**
     *
     * @var string
     */
    public $currency;

    /**
     *
     * @var BankAccount
     */
    private $_bankAccount;

    /**
     *
     * @var TransactionList
     */
    private $_transactionList;

    /**
     *
     * @return BankAccount
     */
    public function getBankAccount()
    {
        return $this->_bankAccount;
    }

    /**
     *
     * @param BankAccount $bankAccount
     *
     * @return \Realejo\Ofx\Banking\BankStatement\Response
     */
    public function setBankAccount(BankAccount $bankAccount)
    {
        $this->_bankAccount = $bankAccount;
        return $this;
    }

    /**
     *
     * @return TransactionList
     */
    public function getTransactionList()
    {
        return $this->_transactionList;
    }

    /**
     *
     * @param TransactionList $transactionList
     *
     * @return \Realejo\Ofx\Banking\BankStatement\Response
     */
    public function setTransactionList(TransactionList $transactionList)
    {
        $this->_transactionList = $transactionList;
        return $this;
    }
}
