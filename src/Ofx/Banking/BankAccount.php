<?php
namespace Realejo\Ofx\Banking;

class BankAccount
{
    const TYPE_CHECKING   = 'CHECKING';
    const TYPE_SAVINGS    = 'SAVINGS';
    const TYPE_MONEYMRKT  = 'MONEYMRKT';
    const TYPE_CREDITLINE = 'CREDITLINE';

    public $bankId;
    public $branchId;
    public $accountId;
    public $accountType;
    public $accountKey;

}
