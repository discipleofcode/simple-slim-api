<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 17.04.17
 * Time: 00:50
 */

namespace src\interfaces;

interface PaymentMethodFactoryInterface
{
    public function createPaymentMethod(string $type);
}