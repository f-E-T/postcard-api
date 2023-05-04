<?php

use Fet\PostcardApi\Postcard;
use PHPUnit\Framework\TestCase;
use Fet\PostcardApi\PostcardCreator;
use Fet\PostcardApi\Postcard\Resource as PostcardResource;
use Fet\PostcardApi\Contracts\PostcardGateway as PostcardContract;
use Fet\PostcardApi\Contracts\Campaign as CampaignContract;
use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use Fet\PostcardApi\Contracts\Authentication as AuthenticationContract;
use Fet\PostcardApi\Campaign;
use Fet\PostcardApi\Response\CampaignStatistic as CampaignStatisticResponse;
use Fet\PostcardApi\Response\DefaultResponse;

class PostcardCreatorTest extends TestCase
{
    /** @test */
    public function it_creates_a_postcard()
    {
        $authenticationResponse = Mockery::mock(AuthenticationResponse::class);
        $authenticationGateway = Mockery::mock(AuthenticationContract::class);
        $defaultResponse = new DefaultResponse();
        $defaultResponse->setCardKey('card-key');
        $defaultResponse->setWarnings([]);
        $defaultResponse->setErrors([]);
        $authenticationGateway
            ->shouldReceive('authenticate')
            ->andReturn($authenticationResponse);

        $postcardGateway = Mockery::mock(PostcardContract::class);
        $postcardGateway
            ->shouldReceive('create')
            ->andReturn($defaultResponse);

        $postcardGateway
            ->shouldReceive('setRecipientAddress')
            ->andReturn($defaultResponse);

        $postcardGateway
            ->shouldReceive('setSenderAddress')
            ->andReturn($defaultResponse);

        $postcardGateway
            ->shouldReceive('setImage')
            ->andReturn($defaultResponse);

        $postcardGateway
            ->shouldReceive('setSenderText')
            ->andReturn($defaultResponse);

        $campaignGateway = Mockery::spy(CampaignContract::class);

        $postcardCreator = new PostcardCreator(
            'campaign-key',
            $authenticationGateway,
            $postcardGateway,
            $campaignGateway
        );

        $recipientAddress = [
            'title' => 'title',
            'lastname' => 'last-name',
            'firstname' => 'first-name',
            'company' => 'company',
            'street' => 'street',
            'houseNr' => 'house-nr',
            'zip' => 'zip',
            'city' => 'city',
            'country' => 'country',
            'poBox' => 'po-box',
            'additionalAdrInfo' => 'additional-adr-info',
        ];

        $senderAddress = [
            'lastname' => 'last-name',
            'firstname' => 'first-name',
            'company' => 'company',
            'street' => 'street',
            'houseNr' => 'house-nr',
            'zip' => 'zip',
            'city' => 'city',
        ];

        $image = 'path-to-image';

        $text = 'sender-text';

        $postcard = $postcardCreator->create($recipientAddress, $senderAddress, $image, $text);

        $this->assertInstanceOf(Postcard::class, $postcard);
        $this->assertEquals('last-name', $postcard->getResource()->getRecipientAddress()->getLastname());
        $this->assertEquals('first-name', $postcard->getResource()->getSenderAddress()->getFirstName());
        $this->assertEquals('path-to-image', $postcard->getResource()->getImage()->getPath());
        $this->assertEquals('sender-text', $postcard->getResource()->getSenderText()->getText());
    }

    /** @test */
    public function it_retrieves_a_postcard()
    {
        $authenticationResponse = Mockery::mock(AuthenticationResponse::class);
        $defaultResponse = new DefaultResponse();
        $defaultResponse->setCardKey('card-key');
        $authenticationGateway = Mockery::mock(AuthenticationContract::class);
        $authenticationGateway
            ->shouldReceive('authenticate')
            ->andReturn($authenticationResponse);

        $postcardGateway = Mockery::mock(PostcardContract::class);
        $postcardGateway
            ->shouldReceive('create')
            ->withArgs(['campaign-key'])
            ->andReturn($defaultResponse);

        $campaignGateway = Mockery::mock(CampaignContract::class);

        $postcardCreator = new PostcardCreator(
            'campaign-key',
            $authenticationGateway,
            $postcardGateway,
            $campaignGateway
        );

        $postcard = $postcardCreator->get('card-key');

        $this->assertInstanceOf(PostcardResource::class, $postcard->getResource());
    }

    /** @test */
    public function it_returns_a_campaign()
    {
        $authenticationResponse = Mockery::mock(AuthenticationResponse::class);
        $authenticationGateway = Mockery::mock(AuthenticationContract::class);
        $authenticationGateway
            ->shouldReceive('authenticate')
            ->andReturn($authenticationResponse);

        $postcardGateway = Mockery::mock(PostcardContract::class);
        $postcardGateway
            ->shouldReceive('createResource')
            ->withArgs(['campaign-key', $authenticationResponse])
            ->andReturn(Mockery::mock(PostcardResource::class));

        $campaignGateway = Mockery::spy(CampaignContract::class);
        $campaignGateway
            ->shouldReceive('getStatistics')
            ->withArgs(['campaign-key', $authenticationResponse])
            ->andReturn(Mockery::mock(CampaignStatisticResponse::class));

        $postcardCreator = new PostcardCreator(
            'campaign-key',
            $authenticationGateway,
            $postcardGateway,
            $campaignGateway
        );

        $this->assertInstanceOf(Campaign::class, $postcardCreator->getCampaign());
    }
}
