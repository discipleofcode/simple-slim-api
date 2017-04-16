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
    /**
     * APIController constructor.
     * @param string $responseType
     */
    public function __construct($responseType = '')
    {
        if ($responseType)
        {
            $this->responseType = $responseType;
        }
    }

    /**
     * It's just a dummy function right now for showing something at [GET] /payments and /payments/{id} endpoint
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function getPayments(Request $request, Response $response, $args) : Response
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

    /**
     * It's just a dummy function right now for showing something at [POST] /payments/{id} endpoint
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function addPayment(Request $request, Response $response, $args)
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
    }

    /**
     * It's just a dummy function right now for showing something at [PUT] /payments/{id} endpoint
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function updatePayment(Request $request, Response $response, $args)
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
    }

    /**
     * It's just a dummy function right now for showing something at [DELETE] /payments/{id} endpoint
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function deletePayment(Request $request, Response $response, $args)
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
    }
}