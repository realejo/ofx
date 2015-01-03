<?php
namespace Realejo\Ofx\Banking;

class Transaction
{

    public $type;

    /**
     * @var \DateTime
     */
    public $datePosted;

    /**
     * @var \DateTime
     */
    public $dateUser;

    /**
     * @var \DateTime
     */
    public $dateAvalilable;

    public $amount;

    public $fitId;

    public $correctFitId;

    public $correctAction;

    public $serverTransactionId;

    public $checkNumber;

    public $referenceNumber;

    public $standardIndustrialCode;

    public $payeeId;

    public $name;

    public $payee;

    public $bankAccountTo;

    public $creditCardAccountTo;

    public $memo;

    public $currency;

    public $originalCurrency;
}
