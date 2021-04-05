<?php

namespace App\DTO;

class AbstractDTO {

    /**
     * This function will check if the key in array exists
     * If key exists then it's value will be taken
     * If does not exist - returns default value
     * @param array $array
     * @param string $key
     * @param mixed|null $defaultValue
     * @return mixed
     */
    public static function checkAndGetKey(array $array, string $key, mixed $defaultValue = null): mixed
    {
        if( array_key_exists($key, $array) ){
            $value = $array[$key];
            return $value;
        }

        return $defaultValue;
    }

}