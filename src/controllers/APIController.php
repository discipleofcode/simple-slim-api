<?php

namespace src\controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use src\helpers\UnifiedRequestHelper;
use src\services\PaymentMethodService;

/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 16.04.17
 * Time: 20:19
 */
class APIController extends AbstractController
{

    private $paymentMethodService;

    /**
     * APIController constructor.
     * @param PaymentMethodService $paymentMethodService
     */
    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
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

            return $this->prepareResponse($request, $response, $data)
                        ->withStatus(404);
        } else {
            $data = [
                'status' => true,
                'data' => [
                    'payments' => [],
                ],
            ];

            return $this->prepareResponse($request, $response, $data);
        }
    }

    /**
     * Main function of this project - it's validating and creating payment object - it's not saving it though
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function addPayment(Request $request, Response $response, $args)
    {
        $params = UnifiedRequestHelper::getMergedParams($request);
        $paymentMethod = $this->paymentMethodService->createPaymentMethod($params);

        if (!count($paymentMethod['errors']))
        {
            $data = [
                'status' => true,
                'data' => [
                    'message' => 'Validated and created (but not saved as we don\'t have db yet)',
                    'payment' => $paymentMethod['object']->toSimpleArray(),
                ],
            ];

            return $this->prepareResponse($request, $response, $data);
        }

        $data = [
            'status' => false,
            'data' => [
                'code' => '400',
                'message' => 'Validation not passed',
                'errors' => $paymentMethod['errors'],
                'payment' => null,
            ],
        ];

        return $this->prepareResponse($request, $response, $data)
                    ->withStatus(400);
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

        return $this->prepareResponse($request, $response, $data)
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

        return $this->prepareResponse($request, $response, $data)
            ->withStatus(404);
    }
}