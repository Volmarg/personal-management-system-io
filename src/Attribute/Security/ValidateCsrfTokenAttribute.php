<?php


namespace App\Attribute\Security;

use Attribute;

/**
 * Class ValidateCsrfTokenAttribute
 * @package App\Attribute\Security
 */
#[Attribute(Attribute::TARGET_METHOD)]
class ValidateCsrfTokenAttribute
{

}