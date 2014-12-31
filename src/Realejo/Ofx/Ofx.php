<?php
namespace Realejo\Ofx;

class Ofx
{

    /**
     * @var array
     */
    private $_headers;

    /**
     * Retorna um header específico, se existir
     *
     * @param string $key
     *
     * @return string
     */
    public function getHeader($key = null)
    {
        return (isset($this->_headers[$key])) ? $this->_headers[$key] : null;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->_headers;
    }

    /**
     * @param string $headers
     * @return \Realejo\Ofx\Ofx
     */
    public function setHeaders($headers)
    {
        $this->_headers = $headers;
        return $this;
    }
}
