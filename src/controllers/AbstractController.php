<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 16.04.17
 * Time: 21:37
 */

namespace src\controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use src\helpers\XMLHelper;

abstract class AbstractController
{
    protected $responseType = 'application/json';

    /**
     * @param Request $request
     * @param Response $response
     * @param $data
     * @return Response
     */
    public function prepareResponse(Request $request, Response $response, $data) : Response
    {
        if ($request->getHeader('CONTENT_TYPE')) $this->responseType = $request->getHeader('CONTENT_TYPE')[0];

        switch ($this->responseType)
        {
            case 'application/json':
                return $response->withJson($data);
            case 'application/xml':
                $xml = new \SimpleXMLElement('<root/>');
                XMLHelper::arrayToXml($data, $xml);
                return $response->withHeader('CONTENT_TYPE', 'application/xml')
                                ->write($xml->asXML());
            default:
                return $response->withJson($data);
        }
    }
}