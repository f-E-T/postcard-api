<?php

use PHPUnit\Framework\TestCase;
use Fet\PostcardApi\Authentication;
use Fet\PostcardApi\Contracts\Authentication as AuthenticationContract;
use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use Fet\PostcardApi\Exception\AuthenticationException;

class AuthenticationTest extends TestCase
{
    /** @test */
    public function it_returns_an_authentication_response_if_it_was_successful()
    {
        $response = new AuthenticationResponse();
        $response->setAccessToken('1234567890');

        $authentication = new Authentication($this->getAuthenticationGateway($response));

        $this->assertFalse($authentication->isAuthenticated());
        $this->assertInstanceOf(AuthenticationResponse::class, $authentication->authenticate());
        $this->assertTrue($authentication->isAuthenticated());
    }

    /** @test */
    public function it_throws_an_exception_if_it_was_unsuccessful()
    {
        $response = new AuthenticationResponse();
        $response->setError('Error');
        $response->setErrorDescription('Error description');

        $authentication = new Authentication($this->getAuthenticationGateway($response));

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Authentication failed with error: Error (Error description)');

        $authentication->authenticate();
    }

    protected function getAuthenticationGateway($response)
    {
        $gateway = Mockery::mock(AuthenticationContract::class);
        $gateway
            ->shouldReceive('authenticate')
            ->andReturn($response);

        return $gateway;
    }
}
