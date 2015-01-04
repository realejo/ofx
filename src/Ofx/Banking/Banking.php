<?php
namespace Realejo\Ofx\Banking;

use Realejo\Ofx\Banking\Statement;

class Banking
{

    /**
     *
     * @var \Realejo\Ofx\Banking\Statement
     */
    private $_Statement;

    /**
     *
     * @return \Realejo\Ofx\Banking\Statement
     */
    public function getStatement()
    {
        return $this->_Statement;
    }

    /**
     *
     * @param \Realejo\Ofx\Banking\Statement $Statement
     */
    public function setStatement(Statement $Statement)
    {
        $this->_Statement = $Statement;
        return $this;
    }
}
