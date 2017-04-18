<?php

namespace Tests\Functional;

use PhilipBrown\Signature\Request;
use PhilipBrown\Signature\Token;

class APITest extends BaseTestCase
{
    /**
     * Test that the index route won't pass authorization
     */
    public function testPostPaymentsAuthorizationFailed()
    {
        $data = ['method' => 'mobile'];
        $token   = new Token('SOMEBADKEYFORSIGNING', 'SOMEVERYSECRETSECRET');
        $request = new Request('POST', '/api/v1/payments', $data);
        $auth = $request->sign($token);

        $response = $this->runApp('POST', '/api/v1/payments', array_merge($auth, $data));

        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * Test that the index route will pass authorization
     */
    public function testPostPaymentsAuthorizationSucceed()
    {
        $data = ['method' => 'mobile'];
        $token   = new Token('SOMEKEYFORSIGNING', 'SOMEVERYSECRETSECRET');
        $request = new Request('POST', '/api/v1/payments', $data);
        $auth = $request->sign($token);

        $response = $this->runApp('POST', '/api/v1/payments', array_merge($auth, $data));

        $this->assertNotEquals(401, $response->getStatusCode());
    }


}