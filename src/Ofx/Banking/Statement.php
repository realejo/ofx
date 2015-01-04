<?php
namespace Realejo\Ofx\Banking;

use Realejo\Ofx\Banking\Statement\Response as StatementResponse;
use Realejo\Ofx\Banking\Statement\Request as StatementRequest;

class Statement
{

    /**
     * @var StatementRequest
     */
    private $_request;

    /**
     * @var StatementResponse
     */
    private $_response;

    /**
     *
     * @return StatementRequest
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @return StatementResponse
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     *
     * @param StatementRequest $request
     *
     * @return \Realejo\Ofx\Banking\Statement
     */
    public function setRequest(StatementRequest $request)
    {
        $this->_request = $request;
        return $this;
    }

    /**
     *
     * @param StatementResponse $response
     *
     * @return \Realejo\Ofx\Banking\Statement
     */
    public function setResponse(StatementResponse $response)
    {
        $this->_response = $response;
        return $this;
    }

    /**
     * @param string|SimpleXMLElement $content
     *
     * @return \Realejo\Ofx\Banking\Statement
     */
    public static function parse($content)
    {
        // Verifica se é um string
        if (is_string($content)) {
            $content = \Realejo\Ofx\Parser::makeXML($content);
        }

        $Statement = new Statement();

        // Verifica se exite a seção do Signon
        $request  = $content->xpath('//SIGNONMSGSRSV1/SONRQ');
        $SONRS = $content->xpath('//SIGNONMSGSRSV1/SONRS');
        if (count($SONRS) == 1) {
            $SONRS = $SONRS[0];

            $response = new StatementResponse();

            $response->statusCode     = (int) $SONRS->STATUS->CODE;
            $response->statusSeverity = $SONRS->STATUS->SEVERITY;

            $response->date = \Realejo\Ofx\Parser::parseDate($SONRS->DTSERVER);

            $response->language = $SONRS->LANGUAGE;

            $response->fiOrganization = \Realejo\Ofx\Parser::parseDate($SONRS->FI->ORG);
            $response->fiUniqueId     = \Realejo\Ofx\Parser::parseDate($SONRS->FI->FID);

            $Statement->setResponse($response);
        }

        return $Statement;
    }
}
