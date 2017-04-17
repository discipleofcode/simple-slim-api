<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 17.04.17
 * Time: 00:12
 */

namespace src\models;

use src\interfaces\PaymentMethodInterface;

class CreditCardPaymentMethod implements PaymentMethodInterface
{
    const TYPE = 'creditCard';

    /*
     * Credit card number
     */
    private $number;

    private $expirationDate;

    private $CVV2;

    private $email;

    static $validateFields = [
        'CVV2' => ['required', 'isCVV2LengthValid', 'isNumber'],
        'number' => ['required', 'isCreditCardNumberValid'],
        'email' => ['required', 'isEmailValid'],
        'expirationDate' => ['required', 'isExpirationDateValid']
    ];

    /**
     * @return String
     */
    public function getType() : string
    {
        return self::TYPE;
    }

    /**
     * @return int
     */
    public function getNumber() : int
    {
        return $this->number;
    }

    /**
     * @param $cardNumber
     * @return CreditCardPaymentMethod
     */
    public function setNumber($cardNumber) : CreditCardPaymentMethod
    {
        $this->number = $cardNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getExpirationDate() : string
    {
        return $this->expirationDate;
    }

    /**
     * @param string $expirationDate
     * @return CreditCardPaymentMethod
     */
    public function setExpirationDate(string $expirationDate) : CreditCardPaymentMethod
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getCVV2() : int
    {
        return $this->CVV2;
    }

    /**
     * @param int $CVV2
     * @return CreditCardPaymentMethod
     */
    public function setCVV2(int $CVV2) : CreditCardPaymentMethod
    {
        $this->CVV2 = $CVV2;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return CreditCardPaymentMethod
     */
    public function setEmail(string $email) : CreditCardPaymentMethod
    {
        $this->email = $email;

        return $this;
    }
}