<?php
namespace Realejo\Ofx\Banking;

class TransactionList extends \ArrayIterator
{
    /**
     * @var \DateTime
     */
    public $dateStart;

    /**
     * @var \DateTime
     */
    public $dateEnd;

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
