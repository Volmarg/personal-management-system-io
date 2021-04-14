<?php


namespace App\Service;


class ArrayService
{

    /**
     * Will extract value from array - for given key - if no such key is found in array then default value will be returned
     *
     * @param array $array
     * @param string $searchedKey
     * @param mixed $defaultValue
     * @return mixed
     */
    public static function getArrayValueForKey(array $array, string $searchedKey, mixed $defaultValue = null): mixed
    {
        if( array_key_exists($searchedKey, $array) ){
            return $array[$searchedKey];
        }

        return $defaultValue;
    }

}