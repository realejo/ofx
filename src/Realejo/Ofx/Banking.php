<?php
namespace Realejo\Ofx;

use Realejo\Ofx\Banking\BankStatement;
use Realejo\Ofx\Banking\BankStatement\Response as BankStatementResponse;
use Realejo\Ofx\Banking\BankAccount;
use Realejo\Ofx\Banking\TransactionList;
use Realejo\Ofx\Banking\Transaction;

class Banking
{

    /**
     *
     * @var \Realejo\Ofx\Banking\BankStatement
     */
    private $_bankStatement;

    /**
     *
     * @return \Realejo\Ofx\Banking\BankStatement
     */
    public function getBankStatement()
    {
        return $this->_bankStatement;
    }

    /**
     *
     * @param \Realejo\Ofx\Banking\BankStatement $bankStatement
     */
    public function setBankStatement(BankStatement $bankStatement)
    {
        $this->_bankStatement = $bankStatement;
        return $this;
    }

    /**
     * @param string|SimpleXMLElement $content
     *
     * @return \Realejo\Ofx\Banking
     */
    public function parse($content)
    {
        // Verifica se é um string
        if (is_string($content)) {
            $content = \Realejo\Ofx\Parser::makeXML($content);
        }

        $banking = new Banking();

        // Verifica se existe o bloco de Banking
        $BANKMSGSRSV1 = $content->xpath('//BANKMSGSRSV1');
        if (count($BANKMSGSRSV1) == 1) {
            $bankStatement = new BankStatement();

            // Verifica se exite a seção do request Banking
            $STMTTRNRQ = $content->xpath('//BANKMSGSRSV1/STMTTRNRQ');
            if (count($STMTTRNRQ) == 1) {
                throw new \Exception('Baking Request Statment not implemented');
            }

            // Verifica se exite a seção do request Banking
            $STMTTRNRS = $content->xpath('//BANKMSGSRSV1/STMTTRNRS');
            if (count($STMTTRNRS) == 1) {
                $STMTTRNRS = $STMTTRNRS[0];

                $response = new BankStatementResponse();

                // Currency
                $CURDEF = $STMTTRNRS->xpath('//CURDEF');
                if (count($CURDEF) == 1) {
                    $response->currency = $CURDEF[0];
                }

                // Verifica se tem conta do banco
                $BANKACCTFROM = $STMTTRNRS->xpath('//BANKACCTFROM');
                if (count($BANKACCTFROM) == 1) {
                    $BANKACCTFROM = $BANKACCTFROM[0];
                    $bankAccount = new BankAccount();
                    $bankAccount->bankId      = isset($BANKACCTFROM->BANKID)   ? $BANKACCTFROM->BANKID : null;
                    $bankAccount->branchId    = isset($BANKACCTFROM->BRANCHID) ? $BANKACCTFROM->BRANCHID : null;
                    $bankAccount->accountId   = isset($BANKACCTFROM->ACCTID)   ? $BANKACCTFROM->ACCTID : null;
                    $bankAccount->accountType = isset($BANKACCTFROM->ACCTTYPE) ? $BANKACCTFROM->ACCTTYPE : null;
                    $bankAccount->accountKey  = isset($BANKACCTFROM->ACCTKEY)  ? $BANKACCTFROM->ACCTKEY : null;

                    $response->setBankAccount($bankAccount);
                } // end if (count($BANKACCTFROM) == 1)

                // Verifica as transações
                $BANKTRANLIST = $STMTTRNRS->xpath('//BANKTRANLIST');
                if (count($BANKTRANLIST) == 1) {
                    $BANKTRANLIST = $BANKTRANLIST[0];

                    $transactionList = new TransactionList();

                    $transactionList->dateStart = Parser::parseDate($BANKTRANLIST->DTSTART);
                    $transactionList->dateEnd   = Parser::parseDate($BANKTRANLIST->DTEND);

                    $STMTTRN = $BANKTRANLIST->xpath('//STMTTRN');
                    if (count($STMTTRN) > 0) {
                        foreach ($STMTTRN as $S) {
                            $transaction = new Transaction();

                            $transaction->type = $S->TRNTYPE;
                            $transaction->datePosted = $S->DTPOSTED;
                            $transaction->dateUser = $S->DTUSER;
                            $transaction->dateAvalilable = $S->DTAVAIL;

                            $transaction->amount = $S->TRNAMT;
                            $transaction->fitId = $S->FITID;
                            $transaction->correctFitId = $S->CORRECTFITID;
                            $transaction->correctAction = $S->CORRECTACTION;
                            $transaction->serverTransactionId = $S->SRVRTID;
                            $transaction->checkNumber = $S->CHECKNUM;
                            $transaction->referenceNumber = $S->REFNUM;
                            $transaction->standardIndustrialCode = $S->SIC;

                            $transaction->payeeId = $S->PAYEEID;
                            $transaction->name = $S->NAME;
                            $transaction->payee = $S->PAYEE;

                            $transaction->bankAccountTo = $S->BANKACCTTO;
                            $transaction->creditCardAccountTo = $S->CCACCTTO;

                            $transaction->memo = $S->MEMO;

                            $transaction->currency = $S->CURRENCY;
                            $transaction->originalCurrency = $S->ORIGCURRENCY;

                            $transactionList[] = $transaction;
                        }
                    } // end if (count($STMTTRN) > 0)

                    // Grava o transactionList
                    $response->setTransactionList($transactionList);

                } //end if (count($BANKTRANLIST) == 1)

                // Grava o response
                $bankStatement->setResponse($response);

            } // end if (count($STMTTRNRS) == 1);

            $banking->setBankStatement($bankStatement);
        } // end if (count($BANKMSGSRSV1) == 1)

        return $banking;
    }
}
