<?php
namespace Realejo\Ofx;

/**
 * Ofx Parser Library
 */
class Parser
{
    /**
     * @param string $file
     * @return \Realejo\Ofx\Ofx
     */
    public function createFromFile($file)
    {
        if (file_exists($file)) {
            $this->createFromString(file_get_contents($file));
        } else {
            return false;
        }
    }

    /**
     * @param string $content
     * @return \Realejo\Ofx\Ofx
     */
    public function createFromString($content)
    {
        $this->originalString = $content;
        $content = explode('<OFX>', $content);

        // Cria o objeto Ofx
        $ofx = new Ofx();

        // Define os headers do OFX
        $ofx->setHeaders($this->parseHeaders(trim($content[0])));

        // Verifica se há mais coisda além do header
        if (!isset($OFXContent[1])) {
            return $ofx;
        }

        // Define o conteúdo SGML do OFX
        $xmlContent = '<OFX>' . $content[1];

        // Fix encoding
        $currentEncoding = $ofx->getHeader('ENCODING');
        if ($currentEncoding == 'USASCII') {
            $currentEncoding = 'ASCII';
        }
        if (!empty($currentEncoding) && $currentEncoding !== 'UTF-8') {
            $xmlContent = mb_convert_encoding($xmlContent, 'UTF-8', $currentEncoding);
        }

        // Cria o XML
        $xml = $this->makeXML($xmlContent);

        $ofx->setSignOn($this->parseSignOn());

        //$ofx->setSignup($this->parseSignup());

        //$ofx->setBanking($this->parseBanking());

        //$ofx->setCreditcard($this->parseCreditcard());

        //$ofx->setInvestment($this->parseInvestment());

        //$ofx->setInterbank($this->parseInterbank());

        //$ofx->setWireFundsTransfers($this->parseWireFundsTransfers());

        //$ofx->setPayments($this->parsePayments());

        //$ofx->setGeneralEmail($this->parseGeneralEmail());

        //$ofx->setInvestmentSecurity($this->parseInvestmentSecurity());

        //$ofx->setFIProfile($this->parseFIProfile());

        return $ofx;
    }

    public function parseHeaders($content)
    {
        $headers = array();
        $content = explode("\n", trim($content));
        foreach($content as $h) {
            list($key,$value) = explode(':', trim($h));
            $headers[trim($key)] = trim($value);
        }

        return $headers;
    }

    /**
     * Cria um XML a partir de um string, mesmo com tags incompletas
     *
     * @param string $content
     *
     * @return SimpleXMLElement
     */
    public static function makeXML($content)
    {
        $xml = new \DomDocument();
        $xml->recover=true;
        $xml->loadXML($content);
        return simplexml_import_dom($xml);
    }

    /**
     * @param string $date
     */
    public static function parseDate($date)
    {
        return $date;
    }
}