<?php

namespace Fet\PostcardApi\Contracts;

use Fet\PostcardApi\Response\CampaignStatistic as CampaignStatisticResponse;
use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use Fet\PostcardApi\Response\Error as ErrorResponse;

interface Campaign
{
    public function getStatistics(string $campaignKey, AuthenticationResponse $authenticationResponse): CampaignStatisticResponse|ErrorResponse;
}
