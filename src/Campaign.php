<?php

namespace Fet\PostcardApi;

use Fet\PostcardApi\Contracts\Campaign as CampaignContract;
use Fet\PostcardApi\Exception\CampaignException;
use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use Fet\PostcardApi\Response\CampaignStatistic as CampaignStatisticResponse;
use Fet\PostcardApi\Response\Error as ErrorResponse;

class Campaign
{
    /**
     * @var CampaignStatisticResponse The campaign statistics response object.
     */
    protected CampaignStatisticResponse $statistics;

    /**
     * @param string $campaignKey The unique key identifying the campaign.
     * @param CampaignContract $campaignGateway The gateway to access campaign data.
     * @param AuthenticationResponse $authenticationResponse The authentication response object.
     *
     * @throws CampaignException If the campaign statistics request fails.
     */
    public function __construct(string $campaignKey, CampaignContract $campaignGateway, AuthenticationResponse $authenticationResponse)
    {
        $statistics = $campaignGateway->getStatistics($campaignKey, $authenticationResponse);

        if ($statistics instanceof ErrorResponse) {
            throw new CampaignException(sprintf(
                'Campaign failed with error: %s (%s)',
                $statistics->getCode(),
                $statistics->getMessage()
            ));
        }

        $this->statistics = $statistics;
    }

    /**
     * Get the campaign key.
     *
     * @return string The unique key identifying the campaign.
     */
    public function getCampaignKey(): string
    {
        return $this->statistics->getCampaignKey();
    }

    /**
     * Get the campaign quota.
     *
     * @return int The quota for the campaign.
     */
    public function getQuota(): int
    {
        return $this->statistics->getQuota();
    }

    /**
     * Get the number of created postcards.
     *
     * @return int The number of postcards created for the campaign.
     */
    public function getNumberOfCreatedPostcards(): int
    {
        return $this->statistics->getSendPostcards();
    }

    /**
     * Get the number of available postcards.
     *
     * @return int The number of postcards available to be sent for the campaign.
     */
    public function getNumberOfAvailablePostcards(): int
    {
        return $this->statistics->getFreeToSendPostcards();
    }
}
