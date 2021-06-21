<?php

namespace App\Attribute\Action;

use Attribute;

/**
 * Class ExternalActionAnnotation
 */
#[Attribute(Attribute::TARGET_METHOD)]
class ExternalActionAttribute
{
    const TYPE_ANY       = "ANY";
    const TYPE_INSERTION = "INSERTION";

    const PROPERTY_NAME_TYPE = "type";

    /**
     * @var string $type
     */
    private string $type;

    /**
     * ExternalActionAttribute constructor.
     * @param string $type
     */
    public function __construct(string $type = self::TYPE_INSERTION)
    {
        $this->type = $type;
    }
}