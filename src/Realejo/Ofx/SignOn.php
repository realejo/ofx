<?php
namespace Realejo\Ofx;

use Realejo\Ofx\SignOn\Response as SignOnResponse;
use Realejo\Ofx\SignOn\Request as SignOnRequest;

class SignOn
{

    /**
     * @var SignOnRequest
     */
    private $_request;

    /**
     * @var SignOnResponse
     */
    private $_response;

    /**
     *
     * @return SignOnRequest
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @return SignOnResponse
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     *
     * @param SignOnRequest $request
     *
     * @return \Realejo\Ofx\SignOn
     */
    public function setRequest(SignOnRequest $request)
    {
        $this->_request = $request;
        return $this;
    }

    /**
     *
     * @param SignOnResponse $response
     *
     * @return \Realejo\Ofx\SignOn
     */
    public function setResponse(SignOnResponse $response)
    {
        $this->_response = $response;
        return $this;
    }

    /**
     * @param string|SimpleXMLElement $content
     *
     * @return \Realejo\Ofx\SignOn
     */
    public static function parse($content)
    {
        // Verifica se é um string
        if (is_string($content)) {
            $content = \Realejo\Ofx\Parser::makeXML($content);
        }

        $SignOn = new SignOn();

        // Verifica se exite a seção do Signon
        $request  = $content->xpath('//SIGNONMSGSRSV1/SONRQ');
        $SONRS = $content->xpath('//SIGNONMSGSRSV1/SONRS');
        if (count($SONRS) == 1) {
            $SONRS = $SONRS[0];

            $response = new SignOnResponse();

            $response->statusCode     = (int) $SONRS->STATUS->CODE;
            $response->statusSeverity = $SONRS->STATUS->SEVERITY;

            $response->date = \Realejo\Ofx\Parser::parseDate($SONRS->DTSERVER);

            $response->language = $SONRS->LANGUAGE;

            $response->fiOrganization = \Realejo\Ofx\Parser::parseDate($SONRS->FI->ORG);
            $response->fiUniqueId     = \Realejo\Ofx\Parser::parseDate($SONRS->FI->FID);

            $SignOn->setResponse($response);
        }

        return $SignOn;
    }
}
