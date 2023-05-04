<?php

use Fet\PostcardApi\Gateway\Campaign as CampaignGateway;
use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use Fet\PostcardApi\Response\CampaignStatistic as CampaignStatisticResponse;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

class CampaignGatewayTest extends TestCase
{
    /** @test */
    public function it_returns_statistics()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"campaignKey":"campaign-key","quota":1000,"sendPostcards":100,"freeToSendPostcards":900}'),
            new Response(500, [], '{"error":"Internal Server Error"}')
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client([
            'base_uri' => 'https://www.example.org',
            'handler' => $handlerStack,
        ]);

        $authenticationResponse = new AuthenticationResponse();
        $authenticationResponse->setAccessToken('access-token');

        $campaignGateway = new CampaignGateway($client, 'client-id', 'client-secret');
        $campaignStatisticResponse = $campaignGateway->getStatistics('campaign-key', $authenticationResponse);

        $lastRequest = $mock->getLastRequest();

        $this->assertEquals($lastRequest->getHeaderLine('Authorization'), 'Bearer access-token');
        $this->assertEquals($lastRequest->getHeaderLine('Accept'), 'application/json');

        $this->assertEquals('https', $lastRequest->getUri()->getScheme());
        $this->assertEquals('www.example.org', $lastRequest->getUri()->getHost());
        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertEquals('api/v1/campaigns/campaign-key/statistic', ltrim($lastRequest->getRequestTarget(), '/'));

        $this->assertInstanceOf(CampaignStatisticResponse::class, $campaignStatisticResponse);
        $this->assertEquals($campaignStatisticResponse->getCampaignKey(), 'campaign-key');
        $this->assertEquals($campaignStatisticResponse->getQuota(), '1000');
        $this->assertEquals($campaignStatisticResponse->getSendPostcards(), '100');
        $this->assertEquals($campaignStatisticResponse->getFreeToSendPostcards(), '900');

        $errorResponse = $campaignGateway->getStatistics('campaign-key', $authenticationResponse);
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }
}
