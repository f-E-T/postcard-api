<?php

namespace Fet\PostcardApi;

use GuzzleHttp\Client;
use Fet\PostcardApi\Campaign;
use Fet\PostcardApi\Postcard;
use Fet\PostcardApi\Gateway\Campaign as CampaignGateway;
use Fet\PostcardApi\Gateway\Postcard as PostcardGateway;
use Fet\PostcardApi\Postcard\Resource as PostcardResource;
use Fet\PostcardApi\Contracts\Campaign as CampaignContract;
use Fet\PostcardApi\Contracts\PostcardGateway as PostcardContract;
use Fet\PostcardApi\Gateway\Authentication as AuthenticationGateway;
use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use Fet\PostcardApi\Contracts\Authentication as AuthenticationContract;

class PostcardCreator
{
    protected string $campaignKey;
    protected AuthenticationResponse $authenticationResponse;
    protected PostcardContract $postcardGateway;
    protected CampaignContract $campaignGateway;

    public function __construct(string $campaignKey, AuthenticationContract $authenticationGateway, PostcardContract $postcardGateway, CampaignContract $campaignGateway)
    {
        $this->campaignKey = $campaignKey;
        $this->authenticationResponse = $authenticationGateway->authenticate();
        $this->postcardGateway = $postcardGateway;
        $this->campaignGateway = $campaignGateway;
    }

    public function create(array $recipientAddress, array $senderAddress, string $image, string $text): Postcard
    {
        $postcard = new Postcard($this->campaignKey, $this->postcardGateway, $this->authenticationResponse);
        $postcard->addRecipientAddress($recipientAddress);
        $postcard->addSenderAddress($senderAddress);
        $postcard->addImage($image);
        $postcard->addSenderText($text);

        return $postcard;
    }

    public function get(string $cardKey): Postcard
    {
        $postcard = new Postcard($this->campaignKey, $this->postcardGateway, $this->authenticationResponse);

        $postcardResource = new PostcardResource();
        $postcardResource->setCardKey($cardKey);

        $postcard->setResource($postcardResource);

        return $postcard;
    }

    public function getCampaign(): Campaign
    {
        return new Campaign($this->campaignKey, $this->campaignGateway, $this->authenticationResponse);
    }

    /** @todo write a test for this method */
    public static function factory($uri, $campaignKey, $clientId, $clientSecret): self
    {
        $client = new Client([
            'base_uri' => $uri,
        ]);

        $authenticationGateway = new AuthenticationGateway($client, $clientId, $clientSecret);
        $authenticationResponse = $authenticationGateway->authenticate();

        $postcardGateway = new PostcardGateway($client, $authenticationResponse, $clientId, $clientSecret);
        $campaignGateway = new CampaignGateway($client, $clientId, $clientSecret);

        return new static($campaignKey, $authenticationGateway, $postcardGateway, $campaignGateway);
    }
}
