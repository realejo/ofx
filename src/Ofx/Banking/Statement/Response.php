<?php
namespace Realejo\Ofx\Banking\Statement;

use Realejo\Ofx\Banking\BankAccount;
use Realejo\Ofx\Banking\CreditcardAccount;
use Realejo\Ofx\Banking\TransactionList;
use Realejo\Ofx\Banking\Balance;

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
     * @vr string
     */
    public $marketingInfo;

    /**
     *
     * @var BankAccount
     */
    private $_bankAccount;

    /**
     *
     * @var CreditcardAccount
     */
    private $_creditcardAccount;

    /**
     *
     * @var TransactionList
     */
    private $_transactionList;

    private $_ledgerBalance;

    private $_availableBalance;

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
     * @return \Realejo\Ofx\Banking\Statement\Response
     */
    public function setBankAccount(BankAccount $bankAccount)
    {
        $this->_bankAccount = $bankAccount;
        return $this;
    }

    /**
     *
     * @return CreditcardAccount
     */
    public function getCreditcardAccount()
    {
        return $this->_creditcardAccount;
    }

    /**
     *
     * @param CreditcardAccount $creditcardAccount
     *
     * @return \Realejo\Ofx\Banking\Statement\Response
     */
    public function setCredicardccount(CreditcardAccount $creditcardAccount)
    {
        $this->_creditcardAccount = $creditcardAccount;
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
     * @return \Realejo\Ofx\Banking\Statement\Response
     */
    public function setTransactionList(TransactionList $transactionList)
    {
        $this->_transactionList = $transactionList;
        return $this;
    }

    /**
     *
     * @return Balance
     */
    public function getLedgerBalance()
    {
        return $this->_ledgerBalance;
    }

    /**
     *
     * @return Balance
     */
    public function getAvailableBalance()
    {
        return $this->_availableBalance;
    }

    /**
     *
     * @param Balance $ledgerBalance
     *
     * @return \Realejo\Ofx\Banking\Statement\Response
     *
     */
    public function setLedgerBalance(Balance $ledgerBalance)
    {
        $this->_ledgerBalance = $ledgerBalance;
        return $this;
    }

    /**
     *
     * @param Balance $availableBalance
     *
     * @return \Realejo\Ofx\Banking\Statement\Response
     */
    public function setAvailableBalance(Balance $availableBalance)
    {
        $this->_availableBalance = $availableBalance;
        return $this;
    }
}
