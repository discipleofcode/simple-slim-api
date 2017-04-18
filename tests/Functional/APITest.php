<?php

namespace Tests\Functional;

use PhilipBrown\Signature\Request;
use PhilipBrown\Signature\Token;
use src\helpers\XMLHelper;

class APITest extends BaseTestCase
{

    private $key;
    private $secret;

    public function __construct($key = null, $secret = null)
    {
        parent::__construct();
        $this->key = $key ?? $this->settings['settings']['auth']['key'];
        $this->secret = $secret ?? $this->settings['settings']['auth']['secret'];
    }

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

        $token   = new Token($this->key, $this->secret);
        $request = new Request('POST', '/api/v1/payments', $data);
        $auth = $request->sign($token);

        $response = $this->runApp('POST', '/api/v1/payments', array_merge($auth, $data));

        $this->assertNotEquals(401, $response->getStatusCode());
    }

    /**
     *
     */
    public function testPostCreateMobilePaymentSucceed()
    {
        $data = [
            'method' => 'mobile',
            'mobileNumber' => '798 913 197',
        ];

        $token   = new Token($this->key, $this->secret);
        $request = new Request('POST', '/api/v1/payments', $data);
        $auth = $request->sign($token);

        $response = $this->runApp('POST', '/api/v1/payments', array_merge($auth, $data));

        $responseParams = (array)json_decode($response->getBody()->__toString());
        $responseData = (array)$responseParams['data'];

        $this->assertNotEquals(401, $response->getStatusCode());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Validated and created (but not saved as we don\'t have db yet)', $responseData['message']);
        $this->assertEquals(true, $responseParams['status']);
    }

    /**
     *
     */
    public function testPostCreateCreditCardPaymentSucceed()
    {
        $data = [
            'method' => 'creditCard',
            'number' => '4042370174848340',
            'CVV2' => '454',
            'email' => 'rafael@piazdecki.pl',
            'expirationDate' => '12-2017',
        ];

        $token   = new Token($this->key, $this->secret);
        $request = new Request('POST', '/api/v1/payments', $data);
        $auth = $request->sign($token);

        $response = $this->runApp('POST', '/api/v1/payments', array_merge($auth, $data));

        $responseParams = (array)json_decode($response->getBody()->__toString());
        $responseData = (array)$responseParams['data'];

        $this->assertNotEquals(401, $response->getStatusCode());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Validated and created (but not saved as we don\'t have db yet)', $responseData['message']);
        $this->assertEquals(true, $responseParams['status']);
    }

    /**
     *
     */
    public function testPostJSONCreateCreditCardPaymentSucceed()
    {
        $data = [
            'method' => 'creditCard',
            'number' => '4042370174848340',
            'CVV2' => '454',
            'email' => 'rafael@piazdecki.pl',
            'expirationDate' => '12-2017',
        ];

        $token   = new Token($this->key, $this->secret);
        $request = new Request('POST', '/api/v1/payments', $data);
        $auth = $request->sign($token);


        $response = $this->runApp('POST', '/api/v1/payments', null, json_encode(array_merge($auth, $data)), ['CONTENT_TYPE' => 'application/json']);
        $responseParams = (array)json_decode($response->getBody()->__toString());

        $responseData = (array)$responseParams['data'];

        $this->assertNotEquals(401, $response->getStatusCode());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Validated and created (but not saved as we don\'t have db yet)', $responseData['message']);
        $this->assertEquals(true, $responseParams['status']);
    }

    /**
     *
     */
    public function testPostXMLCreateCreditCardPaymentSucceed()
    {
        $data = [
            'method' => 'creditCard',
            'number' => '4042370174848340',
            'CVV2' => '454',
            'email' => 'rafael@piazdecki.pl',
            'expirationDate' => '12-2017',
        ];

        $token   = new Token($this->key, $this->secret);
        $request = new Request('POST', '/api/v1/payments', $data);
        $auth = $request->sign($token);

        $xml = new \SimpleXMLElement('<root/>');
        XMLHelper::arrayToXml(array_merge($auth, $data), $xml);

        $response = $this->runApp('POST', '/api/v1/payments', null, $xml->asXML(), ['CONTENT_TYPE' => 'application/xml']);

        $responseParams = XMLHelper::xml2array(new \SimpleXMLElement($response->getBody()->__toString()));
        $responseData = (array)$responseParams['data'];

        $this->assertNotEquals(401, $response->getStatusCode());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Validated and created (but not saved as we don\'t have db yet)', $responseData['message']);
        $this->assertEquals('true', $responseParams['status']);
    }

}