<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 16.04.17
 * Time: 22:38
 */

namespace src\helpers;

class XMLHelper
{
    public static function arrayToXml(Array $array, \SimpleXMLElement &$xml)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subNode = $xml->addChild("$key");
                    self::arrayToXml($value, $subNode);
                } else {
                    $subNode = $xml->addChild("item$key");
                    self::arrayToXml($value, $subNode);
                }
            } else {
                if ($value === false) {
                    $xml->addChild("$key", htmlspecialchars("false"));
                } elseif ($value === true) {
                    $xml->addChild("$key", htmlspecialchars("true"));
                } elseif ($value === null) {
                    $xml->addChild("$key", htmlspecialchars("null"));
                } else {
                    $xml->addChild("$key", htmlspecialchars("$value"));
                }
            }
        }
    }
}