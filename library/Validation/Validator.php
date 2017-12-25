<?php 

namespace Library\Validation;

class Validator
{
    public static function typeValidate($type, array $allowedTypes)
    {
        return in_array($type, $allowedTypes);
    }
}
