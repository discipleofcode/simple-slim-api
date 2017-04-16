<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 16.04.17
 * Time: 23:58
 */

namespace src\interfaces;

interface PaymentMethodInterface
{
    public function getType() : string;
}