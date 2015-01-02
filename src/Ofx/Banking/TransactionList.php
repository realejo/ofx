<?php
namespace Realejo\Ofx\Banking;

class TransactionList extends \ArrayIterator
{
    public $dateStart;
    public $dateEnd;

    private $_transactions = array();

    /**
     * Bloqueia a inclusão de objetos que não seja do tipo Transaction
     *
     * @param Transaction $value
     * @throws Exception
     */
    public function append($value)
    {
        if (! $value instanceof Transaction) {
            throw new \InvalidArgumentException('Transaction object expected');
        }
        return parent::append($value);
    }
}
