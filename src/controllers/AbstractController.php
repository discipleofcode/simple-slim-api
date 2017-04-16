<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 16.04.17
 * Time: 21:37
 */

namespace src\controllers;

use Slim\Http\Response;
use src\helpers\XMLHelper;

abstract class AbstractController
{
    protected $responseType = 'application/json';

    /**
     * @param Response $response
     * @param $data
     * @return Response
     */
    public function prepareResponse(Response $response, $data) : Response
    {
        switch ($this->responseType)
        {
            case 'application/json':
                return $response->withJson($data);
            case 'application/xml':
                $xml = new \SimpleXMLElement('<root/>');
                XMLHelper::arrayToXml($data, $xml);
                return $response->withHeader('Content-Type', 'application/xml')
                                ->write($xml->asXML());
            default:
                return $response->withJson($data);
        }
    }
}