<?php

namespace Fet\PostcardApi\Gateway;

use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Fet\PostcardApi\Response\DefaultResponse;
use Fet\PostcardApi\Response\Error as ErrorResponse;
use Fet\PostcardApi\Response\PostcardState as PostcardStateResponse;
use Fet\PostcardApi\Response\Preview as PreviewResponse;
use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use Fet\PostcardApi\Contracts\PostcardGateway as PostcardGatewayContract;

class Postcard implements PostcardGatewayContract
{
    /**
     * @var Client The Guzzle HTTP client instance.
     */
    protected Client $guzzleClient;

    /**
     * @var AuthenticationResponse The authentication response instance.
     */
    protected AuthenticationResponse $authenticationResponse;

    /**
     * @var string The client ID for the API.
     */
    protected string $clientId;

    /**
     * @var string The client secret for the API.
     */
    protected string $clientSecret;

    /**
     * @param Client $guzzleClient The Guzzle HTTP client instance.
     * @param AuthenticationResponse $authenticationResponse The authentication response instance.
     * @param string $clientId The client ID for the API.
     * @param string $clientSecret The client secret for the API.
     */
    public function __construct(Client $guzzleClient, AuthenticationResponse $authenticationResponse, string $clientId, string $clientSecret)
    {
        $this->guzzleClient = $guzzleClient;
        $this->authenticationResponse = $authenticationResponse;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Create a new postcard with the given campaign key.
     *
     * @param string $campaignKey The campaign key.
     * @return DefaultResponse|ErrorResponse The response from the API.
     */
    public function create(string $campaignKey): DefaultResponse|ErrorResponse
    {
        return $this->doRequest(
            'POST',
            "api/v1/postcards?campaignKey={$campaignKey}"
        );
    }

    /**
     * Approve a postcard with the given card key.
     *
     * @param string $cardKey The card key.
     * @return DefaultResponse|ErrorResponse The response from the API.
     */
    public function approve(string $cardKey): DefaultResponse|ErrorResponse
    {
        return $this->doRequest(
            'POST',
            "api/v1/postcards/$cardKey/approval",
        );
    }

    /**
     * Set the recipient address for a postcard with the given card key and address data.
     *
     * @param string $cardKey The card key.
     * @param array $data The recipient address data.
     * @return DefaultResponse|ErrorResponse The response from the API.
     */
    public function setRecipientAddress(string $cardKey, array $data): DefaultResponse|ErrorResponse
    {
        return $this->doRequest(
            'PUT',
            "api/v1/postcards/{$cardKey}/addresses/recipient",
            [
                'json' => $data
            ]
        );
    }

    /**
     * Set the sender address for a postcard with the given card key and address data.
     *
     * @param string $cardKey The card key.
     * @param array $data The sender address data.
     * @return DefaultResponse|ErrorResponse The response from the API.
     */
    public function setSenderAddress(string $cardKey, array $data): DefaultResponse|ErrorResponse
    {
        return $this->doRequest(
            'PUT',
            "api/v1/postcards/{$cardKey}/addresses/sender",
            [
                'json' => $data
            ]
        );
    }

    /**
     * Set the image for a postcard with the given card key and image file path.
     *
     * @param string $cardKey The card key.
     * @param string $image The image file path.
     * @return DefaultResponse|ErrorResponse The response from the API.
     */
    public function setImage(string $cardKey, string $image): DefaultResponse|ErrorResponse
    {
        return $this->doRequest(
            'PUT',
            "api/v1/postcards/{$cardKey}/image",
            [
                'multipart' => [
                    [
                        'name' => 'image',
                        'filename' => $image,
                        'contents' => Utils::tryFopen($image, 'r')
                    ],
                ],
            ]
        );
    }

    /**
     * Set the sender text for a postcard with the given card key and sender text.
     *
     * @param string $cardKey The card key.
     * @param string $senderText The sender text.
     * @return DefaultResponse|ErrorResponse The response from the API.
     */
    public function setSenderText(string $cardKey, string $senderText): DefaultResponse|ErrorResponse
    {
        return $this->doRequest(
            'PUT',
            "api/v1/postcards/{$cardKey}/sendertext?senderText=$senderText",
        );
    }

    /**
     * Get the state of a postcard with the given card key.
     *
     * @param string $cardKey The card key.
     * @return PostcardStateResponse|ErrorResponse The response from the API.
     */
    public function getState(string $cardKey): PostcardStateResponse|ErrorResponse
    {
        return $this->doRequest(
            'GET',
            "api/v1/postcards/{$cardKey}/state",
            [],
            PostcardStateResponse::class
        );
    }

    /**
     * Get the front preview of a postcard with the given card key.
     *
     * @param string $cardKey The card key.
     * @return PreviewResponse|ErrorResponse The response from the API.
     */
    public function getFrontPreview(string $cardKey): PreviewResponse|ErrorResponse
    {
        return $this->doRequest(
            'GET',
            "api/v1/postcards/{$cardKey}/previews/front",
            [],
            PreviewResponse::class
        );
    }

    /**
     * Get the front preview of a postcard with the given card key.
     *
     * @param string $cardKey The card key.
     * @return PreviewResponse|ErrorResponse The response from the API.
     */
    public function getBackPreview(string $cardKey): PreviewResponse|ErrorResponse
    {
        return $this->doRequest(
            'GET',
            "api/v1/postcards/{$cardKey}/previews/back",
            [],
            PreviewResponse::class
        );
    }

    /**
     * Set the branding text for a postcard with the given card key and text data.
     *
     * @param string $cardKey The card key.
     * @param array $data The branding text data.
     * @return DefaultResponse|ErrorResponse The response from the API.
     */
    public function setBrandingText(string $cardKey, array $data): DefaultResponse|ErrorResponse
    {
        return $this->doRequest(
            'PUT',
            "api/v1/postcards/{$cardKey}/branding/text",
            [
                'json' => $data
            ]
        );
    }

    /**
     * Set the branding image for a postcard with the given card key and image file path.
     *
     * @param string $cardKey The card key.
     * @param string $image The image file path.
     * @return DefaultResponse|ErrorResponse The response from the API.
     */
    public function setBrandingImage(string $cardKey, string $image): DefaultResponse|ErrorResponse
    {
        return $this->doRequest(
            'PUT',
            "api/v1/postcards/{$cardKey}/branding/image",
            [
                'multipart' => [
                    [
                        'name' => 'image',
                        'filename' => $image,
                        'contents' => Utils::tryFopen($image, 'r')
                    ],
                ],
            ]
        );
    }

    /**
     * Set the branding QR tag for a postcard with the given card key and tag data.
     *
     * @param string $cardKey The card key.
     * @param array $data The branding QR tag data.
     * @return DefaultResponse|ErrorResponse The response from the API.
     */
    public function setBrandingQrTag(string $cardKey, array $data): DefaultResponse|ErrorResponse
    {
        return $this->doRequest(
            'PUT',
            "api/v1/postcards/{$cardKey}/branding/qrtag",
            [
                'json' => $data,
            ]
        );
    }

    /**
     * Set the branding stamp for a postcard with the given card key and image file path.
     *
     * @param string $cardKey The card key.
     * @param string $image The image file path.
     * @return DefaultResponse|ErrorResponse The response from the API.
     */
    public function setBrandingStamp(string $cardKey, string $image): DefaultResponse|ErrorResponse
    {
        return $this->doRequest(
            'PUT',
            "api/v1/postcards/{$cardKey}/branding/stamp",
            [
                'multipart' => [
                    [
                        'name' => 'stamp',
                        'filename' => $image,
                        'contents' => Utils::tryFopen($image, 'r')
                    ],
                ],
            ]
        );
    }

    /**
     * Perform an API request with the given method, URI, options, and response class.
     *
     * @param string $method The HTTP method.
     * @param string $uri The URI.
     * @param array $options The request options.
     * @param string $responseClass The response class.
     * @return DefaultResponse|ErrorResponse|PostcardStateResponse|PreviewResponse The response from the API.
     */
    protected function doRequest(string $method, string $uri, array $options = [], string $responseClass = DefaultResponse::class): DefaultResponse|ErrorResponse|PostcardStateResponse|PreviewResponse
    {
        $defaultOptions = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->authenticationResponse->getAccessToken(),
                'Accept' => 'application/json',
            ],
        ];

        $options = array_merge($defaultOptions, $options);

        try {
            $response = $this->guzzleClient->request(
                $method,
                $uri,
                $options
            );

            $json = json_decode($response->getBody()->getContents(), true);
        } catch (BadResponseException $e) {
            return $this->errorResponse($e);
        }

        switch ($responseClass) {
            case PostcardStateResponse::class:
                return $this->stateResponse($json);
            case PreviewResponse::class:
                return $this->previewResponse($json);
        }

        return $this->defaultResponse($json);
    }

    /**
     * Create an ErrorResponse from a BadResponseException.
     *
     * @param BadResponseException $e The exception.
     * @return ErrorResponse The error response.
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

    /**
     * Create a DefaultResponse from an array of JSON data.
     *
     * @param array $json The JSON data.
     * @return DefaultResponse The default response.
     */
    protected function defaultResponse(array $json): DefaultResponse
    {
        $defaultResponse = new DefaultResponse();

        $defaultResponse->setCardKey($json['cardKey'] ?? '');
        $defaultResponse->setSuccessMessage($json['successMessage'] ?? '');
        $defaultResponse->setErrors($json['errors'] ?? '');
        $defaultResponse->setWarnings($json['warnings'] ?? '');

        return $defaultResponse;
    }

    /**
     * Create a PreviewResponse from an array of JSON data.
     *
     * @param array $json The JSON data.
     * @return PreviewResponse The preview response.
     */
    protected function previewResponse(array $json): PreviewResponse
    {
        $previewResponse = new PreviewResponse();

        $previewResponse->setCardKey($json['cardKey'] ?? '');
        $previewResponse->setFileType($json['fileType'] ?? '');
        $previewResponse->setEncoding($json['encoding'] ?? '');
        $previewResponse->setSide($json['side'] ?? '');
        $previewResponse->setImagedata($json['imagedata'] ?? '');
        $previewResponse->setErrors($json['errors'] ?? '');

        return $previewResponse;
    }

    /**
     * Create a PostcardStateResponse from an array of JSON data.
     *
     * @param array $json The JSON data.
     * @return PostcardStateResponse The postcard state response.
     */
    protected function stateResponse(array $json): PostcardStateResponse
    {
        $postcardStateResponse = new PostcardStateResponse();

        $postcardStateResponse->setCardKey($json['cardKey'] ?? '');
        $postcardStateResponse->setState($json['state'] ?? '');
        $postcardStateResponse->setWarnings($json['warnings'] ?? '');

        return $postcardStateResponse;
    }
}
