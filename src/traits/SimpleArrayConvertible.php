<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 18.04.17
 * Time: 04:59
 */

namespace src\traits;

use ReflectionClass;

trait SimpleArrayConvertible
{
    private $hideSimpleArrayFields = [
        'validateFields',
        'allowedFields',
        'hideSimpleArrayFields',
    ];

    function toSimpleArray()
    {
        $reflectionClass = new ReflectionClass(get_class($this));

        $simpleArray = [];
        foreach ($reflectionClass->getProperties() as $property) {
            if (!in_array($property->getName(), $this->hideSimpleArrayFields))
            {
                $property->setAccessible(true);
                $simpleArray[$property->getName()] = $property->getValue($this);
                $property->setAccessible(false);
            }
        }

        return $simpleArray;
    }

    function kupa()
    {
        return 'kupa';
    }
}