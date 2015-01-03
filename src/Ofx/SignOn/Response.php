<?php
namespace Realejo\Ofx\SignOn;

class Response
{

    public $statusCode;

    public $statusSeverity;

    /**
     * @var \DateTime
     */
    public $date;

    public $language;

    public $fiOrganization;

    public $fiUniqueId;
}
