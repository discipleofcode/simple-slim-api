<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 17.04.17
 * Time: 00:51
 */

namespace src\models;

use src\interfaces\PaymentMethodFactoryInterface;
use Exception;

class PaymentMethodFactory implements PaymentMethodFactoryInterface
{

    /**
     * @param string $type
     * @param array $params
     * @return CreditCardPaymentMethod|MobilePaymentMethod
     * @throws Exception
     */
    public function createPaymentMethod(string $type, $params = [])
    {
        switch($type)
        {
            case MobilePaymentMethod::TYPE:
                return new MobilePaymentMethod($params);
                break;
            case CreditCardPaymentMethod::TYPE:
                return new CreditCardPaymentMethod($params);
                break;
        }

        throw new Exception('Payment method type not supported');
    }
}