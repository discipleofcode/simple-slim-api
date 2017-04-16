<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 17.04.17
 * Time: 00:51
 */

namespace src\models;

use src\interfaces\PaymentMethodFactoryInterface;

class PaymentMethodFactory implements PaymentMethodFactoryInterface
{
    public function createPaymentMethod(string $type)
    {
        switch($type)
        {
            case MobilePaymentMethod::TYPE:
                return new MobilePaymentMethod();
                break;
            case CreditCardPaymentMethod::TYPE:
                return new CreditCardPaymentMethod();
                break;
        }
    }
}