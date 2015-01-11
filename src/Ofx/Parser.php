<?php
namespace Realejo\Ofx;

use Realejo\Ofx\Banking\Parser as BankingParser;

/**
 * Ofx Parser Library
 */
class Parser
{
    /**
     * @param string $file
     *
     * @return \Realejo\Ofx\Ofx
     */
    public static function createFromFile($file)
    {
        if (file_exists($file)) {
            return self::createFromString(file_get_contents($file));
        } else {
            return false;
        }
    }

    /**
     * @param string $content
     *
     * @return \Realejo\Ofx\Ofx
     */
    public static function createFromString($content)
    {
        $content = explode('<OFX>', $content);

        // Cria o objeto Ofx
        $ofx = new Ofx();

        // Define os headers do OFX
        $ofx->setHeaders(self::parseHeaders(trim($content[0])));

        // Verifica se há mais coisda além do header
        if (!isset($content[1])) {
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
        $xml = self::makeXML($xmlContent);

        $parse = SignOn::parse($xml);
        if (!empty($parse)) {
            $ofx->setSignOn($parse);
        }

        //$ofx->setSignup($this->parseSignup());

        $parse = BankingParser::parse($xml);
        if (!empty($parse)) {
            $ofx->setBanking($parse);
        }

        //$ofx->setInvestment($this->parseInvestment());

        //$ofx->setInterbank($this->parseInterbank());

        //$ofx->setWireFundsTransfers($this->parseWireFundsTransfers());

        //$ofx->setPayments($this->parsePayments());

        //$ofx->setGeneralEmail($this->parseGeneralEmail());

        //$ofx->setInvestmentSecurity($this->parseInvestmentSecurity());

        //$ofx->setFIProfile($this->parseFIProfile());

        return $ofx;
    }

    public static function parseHeaders($content)
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
        $content = preg_replace('/>\s+</', '><', $content);
        $content = preg_replace('/[\s\n]+</', '<', $content);
        $content = preg_replace('/>[\s\n]+/', '>', $content);

        // Fecha todo mundo
        $content = preg_replace('/<(\w+?)>([^<]+)/', '<\1>\2</\1>', $content);

        // Remove os duplicados.
        // Teoricamente pode existir, mas neste foramto de arquivo não.
        $content = preg_replace('/<\/(\w+?)><\/\1>/', '</\1>', $content);

        // Corrige o content
        if (strpos($content, '<xml>') === false) {
            $content = "<xml>$content</xml>";
        }

        if (strpos($content, '<?') === false) {
            $content = '<?xml version="1.0"?>' . $content;
        }

        $xml = new \DomDocument();

        $xml->loadXML(trim($content));

        return simplexml_import_dom($xml);
    }

    /**
     * @param string $date
     * @return \DateTime
     */
    public static function parseDate($date)
    {
        // Verifica se alguma data foi passada
        if (empty($date)) {
            return null;
        }

        $date = self::parseString($date);

        // remove o Timezone abreviation pois não consegue fazer o parse
        //@todo descobrir pq não faz!
        if (strpos($date, '[') !== false) {
            $date = explode(':', $date);
            $date = $date[0] .']';
        }

        // Testa varios formatos até achar o correto
        foreach (array(
            'Ymd', 'Ymdhis', 'YmdHis', 'Ymdhis.u', 'YmdHis.u',
            'Ymd[O]', 'Ymdhis[O]', 'YmdHis[O]', 'Ymdhis.u[O]', 'YmdHis.u[O]'
        ) as $format) {
            $dateTime = \DateTime::createFromFormat($format, $date);
            if ($dateTime !== false) {
                return $dateTime;
            }
        }
        // Se não conseguiu em nenhuma das opções acima retorna false
        return false;
    }

    /**
     * As vezes um elemente se estiver com quebra de linha, ele não processa direito
     * Ex: <TAG>
     * conteudo
     * </TAG>
     *
     * retorna array('0'=>'
     * conteudo
     * ')
     * @param string|array|SimpleXMLElement $content
     *
     * @return string
     */
    public static function parseString($content)
    {
        if (is_string($content)) {
            return trim($content);
        }

        if (is_object($content)) {
            $content = (array) $content;
        }

        if (is_array($content) and count($content) == 1) {
            return trim($content[0]);
        }

        return null;
    }
}
