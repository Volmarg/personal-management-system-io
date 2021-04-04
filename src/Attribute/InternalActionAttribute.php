<?php

namespace App\Attribute;

use Attribute;

/**
 * Class InternalActionAnnotation
 */
#[Attribute(Attribute::TARGET_METHOD)]
class InternalActionAttribute
{
    public function __construct(){}
}