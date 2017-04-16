<?php

namespace src\controllers;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 16.04.17
 * Time: 20:19
 */
class APIController extends AbstractController
{
    public function __construct($responseType = '')
    {
        if ($responseType)
        {
            $this->responseType = $responseType;
        }
    }

    public function getPayments(Request $request, Response $response, $args)
    {
        if (isset($args['id']))
        {
            $data = [
                'status' => false,
                'data' => [
                    'code' => '404',
                    'message' => 'Payment not found',
                    'payment' => null,
                ],
            ];

            return $this->prepareResponse($response, $data)
                        ->withStatus(404);
        } else {
            $data = [
                'status' => true,
                'data' => [
                    'payments' => [],
                ],
            ];

            return $this->prepareResponse($response, $data);
        }
    }


}