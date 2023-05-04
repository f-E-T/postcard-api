<?php

use Fet\PostcardApi\Gateway\Authentication as AuthenticationGateway;
use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

class AuthenticationGatewayTest extends TestCase
{
    /** @test */
    public function it_returns_an_access_token()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"access_token":"xyz","token_type":"Bearer","expires_in":300}'),
            new Response(500, [], '{"error":"Internal Server Error"}')
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client([
            'base_uri' => 'https://www.example.org',
            'handler' => $handlerStack,
        ]);

        $authenticationGateway = new AuthenticationGateway($client, 'client-id', 'client-secret');
        $authenticationResponse = $authenticationGateway->authenticate();

        $lastRequest = $mock->getLastRequest();
        $formParams = [];
        parse_str($lastRequest->getBody(), $formParams);

        $this->assertEquals('https', $lastRequest->getUri()->getScheme());
        $this->assertEquals('www.example.org', $lastRequest->getUri()->getHost());
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('/OAuth/token', $lastRequest->getRequestTarget());

        $this->assertEquals('client-id', $formParams['client_id']);
        $this->assertEquals('client-secret', $formParams['client_secret']);
        $this->assertEquals('PCCAPI', $formParams['scope']);
        $this->assertEquals('client_credentials', $formParams['grant_type']);

        $this->assertInstanceOf(AuthenticationResponse::class, $authenticationResponse);
        $this->assertEquals($authenticationResponse->getAccessToken(), 'xyz');
        $this->assertEquals($authenticationResponse->getTokenType(), 'Bearer');
        $this->assertEquals($authenticationResponse->getExpiresIn(), '300');

        $errorResponse = $authenticationGateway->authenticate();
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }
}
