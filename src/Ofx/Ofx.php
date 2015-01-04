<?php
namespace Realejo\Ofx;

class Ofx
{

    /**
     * @var array
     */
    private $_headers;

    /**
     * @var \Realejo\Ofx\SignOn
     */
    private $_signOn;

    // private $_signup;

    private $_banking;

    private $_creditcard;

    // private $_investment;

    // private $_interbank;

    // private $_wireFundsTransfers;

    // private $_payments;

    // private $_generalEmail;

    // private $_investmentSecurity;

    // private $_fIProfile;

    /**
     * Retorna um header específico, se existir
     *
     * @param string $key
     *
     * @return string
     */
    public function getHeader($key)
    {
        return (!empty($key) && array_key_exists($key, $this->_headers)) ? $this->_headers[$key] : null;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->_headers;
    }

    /**
     *
     * @param string $headers
     *
     * @return \Realejo\Ofx\Ofx
     */
    public function setHeaders($headers)
    {
        $this->_headers = $headers;
        return $this;
    }

    /**
     *
     * @return \Realejo\Ofx\SignOn
     */
    public function getSignOn()
    {
        return $this->_signOn;
    }

    /**
     *
     * @param \Realejo\Ofx\SignOn $signOn
     *
     * @return \Realejo\Ofx\Ofx
     */
    public function setSignOn(\Realejo\Ofx\SignOn $signOn)
    {
        $this->_signOn = $signOn;

        return $this;
    }

    /**
     *
     * @return \Realejo\Ofx\Banking\Banking
     */
    public function getBanking()
    {
        return $this->_banking;
    }

    /**
     *
     * @param \Realejo\Ofx\Banking\Banking $banking
     *
     * @return \Realejo\Ofx\Ofx
     */
    public function setBanking(\Realejo\Ofx\Banking\Banking $banking)
    {
        $this->_banking = $banking;

        return $this;
    }

    /**
     *
     * @return \Realejo\Ofx\Banking\Banking
     */
    public function getCreditcard()
    {
        return $this->_creditcard;
    }

    /**
     *
     * @param \Realejo\Ofx\Banking\Banking $banking
     *
     * @return \Realejo\Ofx\Ofx
     */
    public function setCreditcard(\Realejo\Ofx\Banking\Banking $creditcard)
    {
        $this->_creditcard = $creditcard;

        return $this;
    }
}
