<?php

namespace Fet\PostcardApi\Gateway;

use Fet\PostcardApi\Contracts\Campaign as CampaignContract;
use Fet\PostcardApi\Response\CampaignStatistic as CampaignStatisticResponse;
use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use Fet\PostcardApi\Response\Error as ErrorResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class Campaign implements CampaignContract
{
    /**
     * @var Client The Guzzle HTTP client used for making API requests.
     */
    protected Client $guzzle;

    /**
     * @var string The client ID for API authentication.
     */
    protected string $clientId;

    /**
     * @var string The client secret for API authentication.
     */
    protected string $clientSecret;

    /**
     * @param Client $guzzle The Guzzle HTTP client instance.
     * @param string $clientId The client ID for API authentication.
     * @param string $clientSecret The client secret for API authentication.
     */
    public function __construct(Client $guzzle, string $clientId, string $clientSecret)
    {
        $this->guzzle = $guzzle;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Get campaign statistics.
     *
     * @param string $campaignKey The unique key identifying the campaign.
     * @param AuthenticationResponse $authenticationResponse The authentication response object.
     *
     * @return CampaignStatisticResponse|ErrorResponse The campaign statistics response or an error response.
     */
    public function getStatistics(string $campaignKey, AuthenticationResponse $authenticationResponse): CampaignStatisticResponse|ErrorResponse
    {
        $campaignStatisticResponse = new CampaignStatisticResponse();

        try {
            $response = $this->guzzle->request(
                'GET',
                "api/v1/campaigns/{$campaignKey}/statistic",
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $authenticationResponse->getAccessToken(),
                        'Accept' => 'application/json',
                    ],
                ]
            );

            $json = json_decode($response->getBody()->getContents(), true);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            $json = json_decode($response->getBody()->getContents(), true);

            $errorResponse = new ErrorResponse();
            $errorResponse->setCode($response->getStatusCode());
            $errorResponse->setMessage($json['error'] ?? '');

            return $errorResponse;
        }

        $campaignStatisticResponse->setCampaignKey($json['campaignKey'] ?? '');
        $campaignStatisticResponse->setQuota($json['quota'] ?? '');
        $campaignStatisticResponse->setSendPostcards($json['sendPostcards'] ?? '');
        $campaignStatisticResponse->setFreeToSendPostcards($json['freeToSendPostcards'] ?? '');

        return $campaignStatisticResponse;
    }
}
