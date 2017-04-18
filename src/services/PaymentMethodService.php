<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 18.04.17
 * Time: 03:28
 */

namespace src\services;


use src\helpers\ValidatorHelper;
use src\models\PaymentMethodFactory;

class PaymentMethodService
{
    /**
     * @param array $params
     * @return array
     */
    public function createPaymentMethod(array $params) : array
    {
        $validator = $this->validatePaymentMethod($params);
        $paymentMethod = null;

        if (!count($validator->getErrors())) {
            $paymentMethodFactory = new PaymentMethodFactory();
            $paymentMethod = $paymentMethodFactory->createPaymentMethod($params['method'], $params);
        }

        return [
            'errors' => $validator->getErrors(),
            'object' => $paymentMethod,
        ];
    }

    public function validatePaymentMethod(array $params) : ValidatorHelper
    {
        $validator = new ValidatorHelper();
        $validator->validate($params, [
            'method' => ['required', 'isPaymentMethodValid']
        ]);

        if (!count($validator->getErrors())) {
            $paymentMethodFactory = new PaymentMethodFactory();
            $paymentMethod = $paymentMethodFactory->createPaymentMethod($params['method']);

            $validator->validate($params, $paymentMethod::$validateFields);
        }

        return $validator;
    }
}