<?php
namespace Realejo\Ofx\Banking;

class Transaction
{
    public $type;
    public $datePosted;
    public $dateUser;
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
