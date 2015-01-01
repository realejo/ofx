<?php
namespace Realejo\Ofx\Banking;

use Realejo\Ofx\Banking\BankStatement\Response as BankStatementResponse;
use Realejo\Ofx\Banking\BankStatement\Request as BankStatementRequest;

class BankStatement
{

    /**
     * @var BankStatementRequest
     */
    private $_request;

    /**
     * @var BankStatementResponse
     */
    private $_response;

    /**
     *
     * @return BankStatementRequest
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @return BankStatementResponse
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     *
     * @param BankStatementRequest $request
     *
     * @return \Realejo\Ofx\Banking\BankStatement
     */
    public function setRequest(BankStatementRequest $request)
    {
        $this->_request = $request;
        return $this;
    }

    /**
     *
     * @param BankStatementResponse $response
     *
     * @return \Realejo\Ofx\Banking\BankStatement
     */
    public function setResponse(BankStatementResponse $response)
    {
        $this->_response = $response;
        return $this;
    }

    /**
     * @param string|SimpleXMLElement $content
     *
     * @return \Realejo\Ofx\Banking\BankStatement
     */
    public static function parse($content)
    {
        // Verifica se é um string
        if (is_string($content)) {
            $content = \Realejo\Ofx\Parser::makeXML($content);
        }

        $BankStatement = new BankStatement();

        // Verifica se exite a seção do Signon
        $request  = $content->xpath('//SIGNONMSGSRSV1/SONRQ');
        $SONRS = $content->xpath('//SIGNONMSGSRSV1/SONRS');
        if (count($SONRS) == 1) {
            $SONRS = $SONRS[0];

            $response = new BankStatementResponse();

            $response->statusCode     = (int) $SONRS->STATUS->CODE;
            $response->statusSeverity = $SONRS->STATUS->SEVERITY;

            $response->date = \Realejo\Ofx\Parser::parseDate($SONRS->DTSERVER);

            $response->language = $SONRS->LANGUAGE;

            $response->fiOrganization = \Realejo\Ofx\Parser::parseDate($SONRS->FI->ORG);
            $response->fiUniqueId     = \Realejo\Ofx\Parser::parseDate($SONRS->FI->FID);

            $BankStatement->setResponse($response);
        }

        return $BankStatement;
    }
}
