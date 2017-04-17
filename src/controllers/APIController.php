<?php

namespace src\controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use src\helpers\ValidatorHelper;
use src\helpers\XMLHelper;
use src\models\PaymentMethodFactory;

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
     * It's just a dummy function right now for showing something at [POST] /payments endpoint
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function addPayment(Request $request, Response $response, $args)
    {
        $validator = new ValidatorHelper();

        //@TODO move it somewhere higher
        $req = [];
        foreach ($request->getParsedBody() as $key => $param) {
            $req[$key] = XMLHelper::xml2array($param)[0];
        }

        $validator->validate($req, [
            'method' => ['required', 'isPaymentMethodValid']
        ]);

        if (!count($validator->getErrors()))
        {
            $paymentMethodFactory = new PaymentMethodFactory();
            $paymentMethod = $paymentMethodFactory->createPaymentMethod($req['method']);

            $validator->validate($req, $paymentMethod::$validateFields);

            if (!count($validator->getErrors())) {
                //@TODO make it more abstract
                switch ($paymentMethod->getType())
                {
                    case 'mobile':
                        $paymentMethod->setMobileNumber((string)$req['mobileNumber']);
                        break;
                    case 'creditCard':
                        $paymentMethod->setCVV2((int)$req['CVV2']);
                        $paymentMethod->setExpirationDate((string)$req['expirationDate']);
                        $paymentMethod->setNumber((int)$req['number']);
                        $paymentMethod->setEmail((string)$req['email']);
                        break;
                }

                $data = [
                    'status' => true,
                    'data' => [
                        'message' => 'Validated and created (but not saved)'
                    ],
                ];

                return $this->prepareResponse($response, $data);
            }
        }

        $data = [
            'status' => false,
            'data' => [
                'code' => '400',
                'message' => 'Validation not passed',
                'errors' => $validator->getErrors(),
                'payment' => null,
            ],
        ];

        return $this->prepareResponse($response, $data)
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