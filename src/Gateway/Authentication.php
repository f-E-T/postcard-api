<?php

namespace Fet\PostcardApi\Gateway;

use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use Fet\PostcardApi\Contracts\Authentication as AuthenticationContract;
use Fet\PostcardApi\Response\Error as ErrorResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class Authentication implements AuthenticationContract
{
    /**
     * @var Client The Guzzle HTTP client instance.
     */
    protected Client $guzzle;

    /**
     * @var string The OAuth client ID.
     */
    protected string $clientId;

    /**
     * @var string The OAuth client secret.
     */
    protected string $clientSecret;

    /**
     * @param Client $guzzle The Guzzle HTTP client instance.
     * @param string $clientId The OAuth client ID.
     * @param string $clientSecret The OAuth client secret.
     */
    public function __construct(Client $guzzle, string $clientId, string $clientSecret)
    {
        $this->guzzle = $guzzle;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Authenticate the user via the OAuth gateway and return an authentication response or error response.
     *
     * @return AuthenticationResponse|ErrorResponse The authentication response or error response.
     */
    public function authenticate(): AuthenticationResponse|ErrorResponse
    {
        $authenticationResponse = new AuthenticationResponse();
        try {
            $response = $this->guzzle->request('POST', '/OAuth/token', [
                'form_params' => [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'scope' => 'PCCAPI',
                    'grant_type' => 'client_credentials'
                ]
            ]);

            $json = json_decode($response->getBody()->getContents(), true);
        } catch (BadResponseException $e) {
            return $this->errorResponse($e);
        }

        $authenticationResponse->setAccessToken($json['access_token'] ?? '');
        $authenticationResponse->setTokenType($json['token_type'] ?? '');
        $authenticationResponse->setExpiresIn($json['expires_in'] ?? '');

        return $authenticationResponse;
    }

    /**
     * Handle an error response from the OAuth gateway and return an error response object.
     *
     * @param BadResponseException $e The exception thrown by Guzzle on a bad response.
     * @return ErrorResponse The error response object.
     */
    protected function errorResponse(BadResponseException $e): ErrorResponse
    {
        $response = $e->getResponse();
        $json = json_decode($response->getBody()->getContents(), true);

        $errorResponse = new ErrorResponse();
        $errorResponse->setCode($response->getStatusCode());
        $errorResponse->setMessage($json['error'] ?? '');

        return $errorResponse;
    }
}
