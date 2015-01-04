<?php
namespace Realejo\Ofx\Banking;

use Realejo\Ofx\Parser as OfxParser;
use Realejo\Ofx\Banking\Banking;
use Realejo\Ofx\Banking\BankAccount;
use Realejo\Ofx\Banking\CreditcardAccount;
use Realejo\Ofx\Banking\TransactionList;
use Realejo\Ofx\Banking\Transaction;
use Realejo\Ofx\Banking\Statement\Response as StatementResponse;

/**
 * Ofx Parser Library
 */
class Parser
{
    /**
     * @param string|SimpleXMLElement $content
     *
     * @return \Realejo\Ofx\Banking\Banking
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
            $banking->setStatement(self::parseBankMessage($content));
        }

        // Verifica se existe o bloco de Banking
        $CREDITCARDMSGSRSV1 = $content->xpath('//CREDITCARDMSGSRSV1');
        if (count($CREDITCARDMSGSRSV1) == 1) {
            $banking->setStatement(self::parseCreditcardMessage($content));
        }

        return $banking;
    }

    /**
     *
     * @param string $content
     *
     * @throws \Exception
     *
     * @return \Realejo\Ofx\Banking\Statement
     */
    static public function parseBankMessage($content)
    {
        // Verifica se é um string
        if (is_string($content)) {
            $content = \Realejo\Ofx\Parser::makeXML($content);
        }

        // Verifica se existe o bloco de Banking
        $BANKMSGSRSV1 = $content->xpath('//BANKMSGSRSV1');
        if (count($BANKMSGSRSV1) == 1) {
            $Statement = new Statement();

            // Verifica se exite a seção do request Banking
            $STMTTRNRQ = $content->xpath('//BANKMSGSRSV1/STMTTRNRQ');
            if (count($STMTTRNRQ) == 1) {
                throw new \Exception('Baking Request Statment not implemented');
            }

            // Verifica se exite a seção do request Banking
            $STMTTRNRS = $content->xpath('//BANKMSGSRSV1/STMTTRNRS');
            if (count($STMTTRNRS) == 1) {
                $STMTTRNRS = $STMTTRNRS[0];

                $response = new StatementResponse();

                // Currency
                $CURDEF = $STMTTRNRS->xpath('//CURDEF');
                if (count($CURDEF) == 1) {
                    $response->currency = $CURDEF[0];
                }

                // Verifica se tem conta do banco
                $BANKACCTFROM = $STMTTRNRS->xpath('//BANKACCTFROM');
                if (count($BANKACCTFROM) == 1) {
                    $response->setBankAccount(self::parseBankAccount($STMTTRNRS));
                }

                // Verifica as transações
                $BANKTRANLIST = $STMTTRNRS->xpath('//BANKTRANLIST');
                if (count($BANKTRANLIST) == 1) {
                    $response->setTransactionList(self::parseTransactions($STMTTRNRS));
                }

                // Grava o response
                $Statement->setResponse($response);

            } // end if (count($STMTTRNRS) == 1);

        } // end if (count($BANKMSGSRSV1) == 1)

        return $Statement;
    }

    /**
     *
     * @param string $content
     *
     * @throws \Exception
     *
     * @return \Realejo\Ofx\Banking\Statement
     */
    static public function parseCreditcardMessage($content)
    {
        // Verifica se é um string
        if (is_string($content)) {
            $content = \Realejo\Ofx\Parser::makeXML($content);
        }

        // Verifica se existe o bloco de Banking
        $CREDITCARDMSGSRSV1 = $content->xpath('//CREDITCARDMSGSRSV1');
        if (count($CREDITCARDMSGSRSV1) == 1) {
            $Statement = new Statement();

            // Verifica se exite a seção do request
            $STMTTRNRQ = $content->xpath('//CREDITCARDMSGSRSV1/CCSTMTTRNRQ');
            if (count($STMTTRNRQ) == 1) {
                throw new \Exception('Creditcard Request Statment not implemented');
            }

            // Verifica se exite a seção do response
            $STMTTRNRS = $content->xpath('//CREDITCARDMSGSRSV1/CCSTMTTRNRS');
            if (count($STMTTRNRS) == 1) {
                $STMTTRNRS = $STMTTRNRS[0];

                $response = new StatementResponse();

                // Currency
                $CURDEF = $STMTTRNRS->xpath('//CURDEF');
                if (count($CURDEF) == 1) {
                    $response->currency = $CURDEF[0];
                }

                // Verifica se tem conta do banco
                $BANKACCTFROM = $STMTTRNRS->xpath('//CCACCTFROM');
                if (count($BANKACCTFROM) == 1) {
                    $response->setCredicardccount(self::parseCreditcardAccount($STMTTRNRS));
                }

                // Verifica as transações
                $BANKTRANLIST = $STMTTRNRS->xpath('//BANKTRANLIST');
                if (count($BANKTRANLIST) == 1) {
                    $response->setTransactionList(self::parseTransactions($STMTTRNRS));
                }

                // Grava o response
                $Statement->setResponse($response);

            } // end if (count($STMTTRNRS) == 1);

        } // end if (count($BANKMSGSRSV1) == 1)

        return $Statement;
    }

    /**
     *
     * @param string$content
     * @return \Realejo\Ofx\Banking\BankAccount
     */
    static public function parseBankAccount($content)
    {
        // Verifica se é um string
        if (is_string($content)) {
            $content = \Realejo\Ofx\Parser::makeXML($content);
        }

        $bankAccount = new BankAccount();

        $BANKACCTFROM = $content->xpath('//BANKACCTFROM');
        if (count($BANKACCTFROM) == 1) {
            $BANKACCTFROM = $BANKACCTFROM[0];
            $bankAccount->bankId      = isset($BANKACCTFROM->BANKID)   ? OfxParser::parseString($BANKACCTFROM->BANKID) : null;
            $bankAccount->branchId    = isset($BANKACCTFROM->BRANCHID) ? OfxParser::parseString($BANKACCTFROM->BRANCHID) : null;
            $bankAccount->accountId   = isset($BANKACCTFROM->ACCTID)   ? OfxParser::parseString($BANKACCTFROM->ACCTID) : null;
            $bankAccount->accountType = isset($BANKACCTFROM->ACCTTYPE) ? OfxParser::parseString($BANKACCTFROM->ACCTTYPE) : null;
            $bankAccount->accountKey  = isset($BANKACCTFROM->ACCTKEY)  ? OfxParser::parseString($BANKACCTFROM->ACCTKEY) : null;
        } // end if (count($BANKACCTFROM) == 1)

        return $bankAccount;
    }

    /**
     *
     * @param string $content
     * @return \Realejo\Ofx\Banking\CreditcardAccount
     */
    static public function parseCreditcardAccount($content)
    {
        // Verifica se é um string
        if (is_string($content)) {
            $content = \Realejo\Ofx\Parser::makeXML($content);
        }

        $creditcardAccount = new CreditcardAccount();

        $CCACCTFROM = $content->xpath('//CCACCTFROM');
        if (count($CCACCTFROM) == 1) {
            $CCACCTFROM = $CCACCTFROM[0];
            $creditcardAccount->accountId   = isset($CCACCTFROM->ACCTID)   ? OfxParser::parseString($CCACCTFROM->ACCTID) : null;
            $creditcardAccount->accountKey  = isset($CCACCTFROM->ACCTKEY)  ? OfxParser::parseString($CCACCTFROM->ACCTKEY) : null;
        } // end if (count($BANKACCTFROM) == 1)

        return $creditcardAccount;
    }

    public static function parseTransactions($content)
    {
        // Verifica se é um string
        if (is_string($content)) {
            $content = \Realejo\Ofx\Parser::makeXML($content);
        }

        // Verifica as transações
        $BANKTRANLIST = $content->xpath('//BANKTRANLIST');
        if (count($BANKTRANLIST) == 1) {
            $BANKTRANLIST = $BANKTRANLIST[0];

            $transactionList = new TransactionList();

            $transactionList->dateStart = OfxParser::parseDate($BANKTRANLIST->DTSTART);
            $transactionList->dateEnd   = OfxParser::parseDate($BANKTRANLIST->DTEND);

            $STMTTRN = $BANKTRANLIST->xpath('//STMTTRN');
            if (count($STMTTRN) > 0) {
                foreach ($STMTTRN as $S) {
                    $transaction = new Transaction();

                    $transaction->type = OfxParser::parseString($S->TRNTYPE);
                    $transaction->datePosted = OfxParser::parseDate($S->DTPOSTED);
                    $transaction->dateUser = OfxParser::parseDate($S->DTUSER);
                    $transaction->dateAvalilable = OfxParser::parseDate($S->DTAVAIL);

                    $transaction->amount = (float) $S->TRNAMT;
                    $transaction->fitId = OfxParser::parseString($S->FITID);
                    $transaction->correctFitId = OfxParser::parseString($S->CORRECTFITID);
                    $transaction->correctAction = OfxParser::parseString($S->CORRECTACTION);
                    $transaction->serverTransactionId = OfxParser::parseString($S->SRVRTID);
                    $transaction->checkNumber = OfxParser::parseString($S->CHECKNUM);
                    $transaction->referenceNumber = OfxParser::parseString($S->REFNUM);
                    $transaction->standardIndustrialCode = OfxParser::parseString($S->SIC);

                    $transaction->payeeId = OfxParser::parseString($S->PAYEEID);
                    $transaction->name = OfxParser::parseString($S->NAME);
                    $transaction->payee = OfxParser::parseString($S->PAYEE);

                    $transaction->bankAccountTo = OfxParser::parseString($S->BANKACCTTO);
                    $transaction->creditCardAccountTo = OfxParser::parseString($S->CCACCTTO);

                    $transaction->memo = OfxParser::parseString($S->MEMO);

                    $transaction->currency = OfxParser::parseString($S->CURRENCY);
                    $transaction->originalCurrency = OfxParser::parseString($S->ORIGCURRENCY);

                    $transactionList[] = $transaction;
                }
            } // end if (count($STMTTRN) > 0)

            // Grava o transactionList
            return $transactionList;

        } //end if (count($BANKTRANLIST) == 1)
    }
}
