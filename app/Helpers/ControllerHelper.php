<?php

namespace App\Helpers;

class ControllerHelper
{
    
    public static function checkParamRoute($controller, $action){
        return is_null($controller) || is_null($action) ? true : false;
    }

    public static function sanitize(array $input): array
    {
        return array_map(function ($value, $key) {
            // Allow string, numeric, boolean
            if (is_string($value)) {
                return trim(strip_tags($value));
            }
            if (is_numeric($value) || is_bool($value)) {
                return $value;
            }
            // Recursively sanitize arrays
            if (is_array($value)) {
                return self::sanitize($value);
            }

            if (is_null($value)) {
                return null;
            }
            
            // Reject objects or unknown types
            throw new \Exception("Invalid data type detected for field [$key]. Only string, number, boolean, and arrays allowed.");
        }, $input, array_keys($input));
    }

}
