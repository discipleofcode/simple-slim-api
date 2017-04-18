<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 16.04.17
 * Time: 23:59
 */

namespace src\models;

use src\interfaces\PaymentMethodInterface;
use src\traits\SimpleArrayConvertible;

class MobilePaymentMethod implements PaymentMethodInterface
{
    use SimpleArrayConvertible;

    const TYPE = 'mobile';

    private $mobileNumber;

    static $validateFields = [
        'mobileNumber' => ['required', 'isPhoneNumberValid'],
    ];

    static $allowedFields = [
        'mobileNumber',
    ];

    public function __construct($params = [])
    {
        foreach ($params as $name => $param) {
            if (in_array($name, self::$allowedFields)) {
                $methodName = 'set' . ucfirst($name);
                $this->$methodName($param);
            }
        }
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public function getMobileNumber() : string
    {
        return $this->mobileNumber;
    }

    /**
     * @param string $mobileNumber
     * @return MobilePaymentMethod
     */
    public function setMobileNumber(string $mobileNumber) : MobilePaymentMethod
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }
}