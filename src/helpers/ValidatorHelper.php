<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 17.04.17
 * Time: 00:55
 */

namespace src\helpers;


use src\models\CreditCardPaymentMethod;
use src\models\MobilePaymentMethod;

class ValidatorHelper
{
    private $errors = [];
    const VALID_PAYMENT_METHODS = [MobilePaymentMethod::TYPE, CreditCardPaymentMethod::TYPE];

    public function isNumber($field, $value) : bool
    {
        return is_numeric((string)$value);
    }

    public function required($field, $value) : bool
    {
        return (strlen((string)$value) > 0);
    }

    public function isCVV2LengthValid($field, $value) : bool
    {
        return (strlen((string)$value) == 3);
    }

    public function isCreditCardNumberValid($field, $value) : bool
    {
        $number = preg_replace('/\D/', '', $value);
        $numberLength = strlen($number);

        $total = 0;
        for ($i = 0; $i < $numberLength; $i++) {
            $digit = $number[$i];

            if ($i % 2 == $numberLength % 2) {
                $digit *= 2;

                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $total += $digit;
        }

        return ($total % 10 == 0);
    }

    public function isExpirationDateValid($field, $value) : bool
    {
        $date = explode('-', $value);

        if (count($date) != 2 || strlen($value) != 7)
        {
            return false;
        }
        elseif ((int)$date[0] > 12 || (int)$date[0] < 1)
        {
            return false;
        }

        $expires = \DateTime::createFromFormat('mY', $date[0].$date[1]);
        $now     = new \DateTime();
        if ($expires < $now) {
            return false;
        }

        return true;
    }

    public function isEmailValid($field, $value) : bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function isPaymentMethodValid($field, $value) : bool
    {
        return in_array($value, self::VALID_PAYMENT_METHODS) ? true : false;
    }

    public function isPhoneNumberValid($field, $value) : bool
    {
        return (bool)preg_match('/^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \/]?)?((?:\(?\d{1,}\)?[\-\.\ \/]?)' .
            '{0,})(?:[\-\.\ \/]?(?:#|ext\.?|extension|x)[\-\.\ \/]?(\d+))?$/i', $value);
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    public function setError($field, $message) : ValidatorHelper
    {
        $this->errors[$field] = $message;

        return $this;
    }

    public function validate($data, $fields)
    {
        foreach ($fields as $field => $rules)
        {
            foreach ($rules as $rule) {
                if (!$this->$rule($field, $data[$field] ?? ($data->$field ?? '')))
                {
                    $this->setError($field, "$field is not valid");
                }
            }
        }
    }
}