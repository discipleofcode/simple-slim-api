<?php
/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 18.04.17
 * Time: 02:29
 */

namespace src\helpers;

use Slim\Http\Request;

class UnifiedRequestHelper
{
    /**
     * Method for merging request params with parsed body params (json, xml)
     *
     * @param Request $request
     * @return array
     */
    public static function getMergedParams(Request $request) : array
    {
        switch ($request->getHeader('Content-Type')) {
            case 'application/xml':
                $data = [];
                foreach ($request->getParsedBody() as $key => $param) {
                    $data[$key] = XMLHelper::xml2array($param)[0];
                }

                return array_merge($data, $request->getParams());

            default:
                if (is_array($request->getParsedBody())) {
                    return array_merge($request->getParsedBody(), $request->getParams());
                } else {
                    return $request->getParams();
                }
        }
    }
}