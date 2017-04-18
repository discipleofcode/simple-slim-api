<?php

/**
 * Created by PhpStorm.
 * User: rafael
 * Date: 18.04.17
 * Time: 00:47
 */

namespace src\middlewares;

use PhilipBrown\Signature\Auth;
use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Guards\CheckKey;
use PhilipBrown\Signature\Guards\CheckVersion;
use PhilipBrown\Signature\Guards\CheckTimestamp;
use PhilipBrown\Signature\Guards\CheckSignature;
use PhilipBrown\Signature\Exceptions\SignatureException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use src\helpers\UnifiedRequestHelper;

class AuthTokenMiddleware
{
    private $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    /**
     * Middleware for authorization with key and timestamp
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next) : ResponseInterface
    {
        $params = UnifiedRequestHelper::getMergedParams($request);

        if (!isset($params['auth_timestamp'])) {
            return $response->withStatus(401, 'Request unauthorized. Please provide auth_timestamp');
        }

        if (!isset($params['auth_version'])) $params['auth_version'] = '5.1.2';

        $auth = new Auth($request->getMethod(), $request->getUri()->getPath(), $params, [
            new CheckKey,
            new CheckVersion,
            new CheckTimestamp(600),
            new CheckSignature,
        ]);

        $token = new Token($this->settings['settings']['auth']['key'], $this->settings['settings']['auth']['secret']);

        try
        {
            $auth->attempt($token);
        }
        catch (SignatureException $e)
        {
            return $response->withStatus(401, 'Request unauthorized. Please provide auth_timestamp, auth_key and auth_signature (hash)');
        }

        return $next($request, $response);
    }
}