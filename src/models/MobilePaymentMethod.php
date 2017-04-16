<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 16.04.17
 * Time: 23:59
 */

namespace src\models;

use src\interfaces\PaymentMethodInterface;

class MobilePaymentMethod implements PaymentMethodInterface
{
    const TYPE = 'mobile';
    private $mobileNumber;

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