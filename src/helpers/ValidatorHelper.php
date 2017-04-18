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
        if (!is_numeric((string)$value)) {
            $this->setError("$field-000", "$field is not a number");
            return false;
        }

        return true;
    }

    public function required($field, $value) : bool
    {
        if (strlen((string)$value) == 0) {
            $this->setError("$field-001", "$field is required");
            return false;
        }
        return true;
    }

    public function isCVV2LengthValid($field, $value) : bool
    {
        if (strlen((string)$value) != 3) {
            $this->setError("$field-002", "$field is not valid. Valid format: 3-digit number");
            return false;
        }

        return true;
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

        if ($total % 10 != 0) {
            $this->setError("$field-003", "Credit card number is not valid.");
            return false;
        }

        return true;
    }

    public function isExpirationDateValid($field, $value) : bool
    {
        $date = explode('-', $value);

        if (count($date) != 2 || strlen($value) != 7)
        {
            $this->setError("$field-004", "$field is not valid. Valid format MM-YYYY ex. 01-2017");
            return false;
        }
        elseif ((int)$date[0] > 12 || (int)$date[0] < 1)
        {
            $this->setError("$field-005", "$field is not valid. Valid format MM-YYYY where MM is between 01 and 12");
            return false;
        }

        $expires = \DateTime::createFromFormat('mY', $date[0].$date[1]);
        $now     = new \DateTime();

        if ($expires < $now) {
            $this->setError("$field-006", "expired or $field is invalid");
            return false;
        }

        return true;
    }

    public function isEmailValid($field, $value) : bool
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->setError("$field-007", "$field is not valid. Please check $field format");
            return false;
        }

        return true;
    }

    public function isPaymentMethodValid($field, $value) : bool
    {
        if (!in_array($value, self::VALID_PAYMENT_METHODS)) {
            $this->setError("$field-008", "$field is not valid payment method. Valid methods: mobile or creditCard");
            return false;
        }
        return true;
    }

    public function isPhoneNumberValid($field, $value) : bool
    {
        if (!(bool)preg_match('/(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\\\/]?)?((?:\(?\d{1,}\)?[\-\.' .
                              '\ \\\\\/]?){0,})(?:[\-\.\ \\\\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\\\/]?(\d+))?/i', $value)) {
            $this->setError("$field-009", "$field is not valid. Possible formats: (+351) 282 43 50 50, 90191919908, 555-8909, " .
                                           "001 6867684, 001 6867684x1, 1 (234) 567-8901, 1-234-567-8901 x1234, " .
                                           "1-234-567-8901 ext1234, 1-234 567.89/01 ext.1234, 1(234)5678901x1234, (123)8575973, " .
                                           "(0055)(123)8575973");
            return false;
        }

        return true;
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    public function setError($code, $message) : ValidatorHelper
    {
        $this->errors[$code] = $message;

        return $this;
    }

    public function validate($data, $fields)
    {
        foreach ($fields as $field => $rules)
        {
            foreach ($rules as $rule) {
                $this->$rule($field, $data[$field] ?? '');
            }
        }
    }
}