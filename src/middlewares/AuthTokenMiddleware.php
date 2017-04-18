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

class AuthTokenMiddleware
{
    /**
     * Middleware for authorization with key and timestamp
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next) : ResponseInterface
    {
        $auth = new Auth($request->getMethod(), $request->getUri()->getPath(), $request->getParsedBody(), [
            new CheckKey,
            new CheckVersion,
            new CheckTimestamp,
            new CheckSignature,
        ]);

        $token = new Token('SOMEKEYFORSIGNING', 'SOMEVERYSECRETSECRET');

        try
        {
            $auth->attempt($token);
        }
        catch (SignatureException $e)
        {
            return $response->withStatus(401);
        }

        return $next($request, $response);
    }
}