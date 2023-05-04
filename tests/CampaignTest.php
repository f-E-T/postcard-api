<?php

use PHPUnit\Framework\TestCase;
use Fet\PostcardApi\Campaign;
use Fet\PostcardApi\Contracts\Campaign as CampaignContract;
use Fet\PostcardApi\Exception\CampaignException;
use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use Fet\PostcardApi\Response\CampaignStatistic as CampaignStatisticResponse;
use Fet\PostcardApi\Response\Error as ErrorResponse;

class CampaignTest extends TestCase
{
    protected $authenticationResponse;

    public function setUp(): void
    {
        $authenticationResponse = new AuthenticationResponse();
        $authenticationResponse->setAccessToken('access-token');

        $this->authenticationResponse = $authenticationResponse;
    }

    /** @test */
    public function it_returns_statistics_if_it_was_successful()
    {
        $campaignStatisticResponse = new CampaignStatisticResponse();
        $campaignStatisticResponse->setCampaignKey('campaign-key');
        $campaignStatisticResponse->setQuota(1000);
        $campaignStatisticResponse->setSendPostcards(900);
        $campaignStatisticResponse->setFreeToSendPostcards(100);

        $campaign = new Campaign('campaign-key', $this->getCampaignGateway($campaignStatisticResponse), $this->authenticationResponse);

        $this->assertEquals('campaign-key', $campaign->getCampaignKey());
        $this->assertEquals(1000, $campaign->getQuota());
        $this->assertEquals(900, $campaign->getNumberOfCreatedPostcards());
        $this->assertEquals(100, $campaign->getNumberOfAvailablePostcards());
    }

    /** @test */
    public function it_throws_an_exception_if_it_was_unsuccessful()
    {
        $errorResponse = new ErrorResponse();
        $errorResponse->setCode(500);
        $errorResponse->setMessage('Internal Server Error');

        $this->expectException(CampaignException::class);
        $this->expectExceptionMessage('Campaign failed with error: 500 (Internal Server Error)');

        new Campaign('campaign-key', $this->getCampaignGateway($errorResponse), $this->authenticationResponse);
    }

    protected function getCampaignGateway($response)
    {
        $campaignGateway = Mockery::mock(CampaignContract::class);
        $campaignGateway
            ->shouldReceive('getStatistics')
            ->withArgs(['campaign-key', $this->authenticationResponse])
            ->andReturn($response);

        return $campaignGateway;
    }
}
