<?php

use Fet\PostcardApi\Gateway\Postcard as PostcardGateway;
use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

class PostcardGatewayTest extends TestCase
{
    /** @test */
    public function it_creates_a_postcard()
    {
        list($postcardGateway, $mock) = $this->getPostcardGateway();

        $defaultResponse = $postcardGateway->create('campaign-key');
        $this->assertRequest('POST', 'api/v1/postcards?campaignKey=campaign-key', $mock);
        $this->assertEquals($defaultResponse->getCardKey(), 'card-key');

        $errorResponse = $postcardGateway->create('campaign-key');
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }

    /** @test */
    public function it_approves_a_postcard()
    {
        list($postcardGateway, $mock) = $this->getPostcardGateway();

        $defaultResponse = $postcardGateway->approve('card-key');
        $this->assertRequest('POST', 'api/v1/postcards/card-key/approval', $mock);
        $this->assertEquals($defaultResponse->getCardKey(), 'card-key');

        $errorResponse = $postcardGateway->approve('card-key');
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }

    /** @test */
    public function it_updates_a_recipient_address()
    {
        list($postcardGateway, $mock) = $this->getPostcardGateway();

        $data = [
            'title' => 'title',
            'lastname' => 'lastname',
            'firstname' => 'firstname',
            'company' => 'company',
            'street' => 'street',
            'houseNr' => 'houseNr',
            'zip' => 'zip',
            'city' => 'city',
            'country' => 'country',
            'poBox' => 'poBox',
            'additionalAdrInfo' => 'additionalAdrInfo',
        ];

        $defaultResponse = $postcardGateway->setRecipientAddress('card-key', $data);
        $this->assertRequest('PUT', 'api/v1/postcards/card-key/addresses/recipient', $mock);

        $lastRequest = $mock->getLastRequest();
        $this->assertEquals($data, json_decode($lastRequest->getBody(), true));
        $this->assertEquals($defaultResponse->getCardKey(), 'card-key');

        $errorResponse = $postcardGateway->setRecipientAddress('card-key', $data);
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }

    /** @test */
    public function it_updates_a_sender_address()
    {
        list($postcardGateway, $mock) = $this->getPostcardGateway();

        $data = [
            'lastname' => 'lastname',
            'firstname' => 'firstname',
            'company' => 'company',
            'street' => 'street',
            'houseNr' => 'houseNr',
            'zip' => 'zip',
            'city' => 'city',
        ];

        $defaultResponse = $postcardGateway->setSenderAddress('card-key', $data);
        $this->assertRequest('PUT', 'api/v1/postcards/card-key/addresses/sender', $mock);

        $lastRequest = $mock->getLastRequest();
        $this->assertEquals($data, json_decode($lastRequest->getBody(), true));
        $this->assertEquals($defaultResponse->getCardKey(), 'card-key');

        $errorResponse = $postcardGateway->setSenderAddress('card-key', $data);
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }

    /** @test */
    public function it_updates_an_image()
    {
        list($postcardGateway, $mock) = $this->getPostcardGateway();

        $image = $this->createImage();

        $defaultResponse = $postcardGateway->setImage('card-key', $image);
        $this->assertRequest('PUT', 'api/v1/postcards/card-key/image', $mock);

        $lastRequest = $mock->getLastRequest();
        $this->assertStringContainsString('name="image"; filename="dummy.jpg"', $lastRequest->getBody()->getContents());
        $this->assertEquals($defaultResponse->getCardKey(), 'card-key');

        $errorResponse = $postcardGateway->setImage('card-key', $image);
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }

    /** @test */
    public function it_updates_a_sender_text()
    {
        list($postcardGateway, $mock) = $this->getPostcardGateway();

        $defaultResponse = $postcardGateway->setSenderText('card-key', 'sender-text');
        $this->assertRequest('PUT', 'api/v1/postcards/card-key/sendertext?senderText=sender-text', $mock);
        $this->assertEquals($defaultResponse->getCardKey(), 'card-key');

        $errorResponse = $postcardGateway->setSenderText('card-key', 'sender-text');
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }

    /** @test */
    public function it_returns_the_actual_state()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"cardKey":"card-key","state":{"state":"state","date":[1970,1,1]},"warnings":[]}'),
            new Response(500, [], '{"error":"Internal Server Error"}')
        ]);

        list($postcardGateway, $mock) = $this->getPostcardGateway($mock);

        $postcardStateResponse = $postcardGateway->getState('card-key');
        $this->assertRequest('GET', 'api/v1/postcards/card-key/state', $mock);
        $this->assertEquals($postcardStateResponse->getCardKey(), 'card-key');

        $errorResponse = $postcardGateway->getState('card-key');
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }

    /** @test */
    public function it_returns_a_preview_of_the_front_side()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"cardKey":"card-key","fileType":"file-type","encoding":"encoding","side":"side","imagedata":"image-data","errors":[]}'),
            new Response(500, [], '{"error":"Internal Server Error"}')
        ]);

        list($postcardGateway, $mock) = $this->getPostcardGateway($mock);

        $previewResponse = $postcardGateway->getFrontPreview('card-key');
        $this->assertRequest('GET', 'api/v1/postcards/card-key/previews/front', $mock);
        $this->assertEquals($previewResponse->getCardKey(), 'card-key');

        $errorResponse = $postcardGateway->getFrontPreview('card-key');
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }

    /** @test */
    public function it_returns_a_preview_of_the_back_side()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"cardKey":"card-key","fileType":"file-type","encoding":"encoding","side":"side","imagedata":"image-data","errors":[]}'),
            new Response(500, [], '{"error":"Internal Server Error"}')
        ]);

        list($postcardGateway, $mock) = $this->getPostcardGateway($mock);

        $previewResponse = $postcardGateway->getBackPreview('card-key');
        $this->assertRequest('GET', 'api/v1/postcards/card-key/previews/back', $mock);
        $this->assertEquals($previewResponse->getCardKey(), 'card-key');

        $errorResponse = $postcardGateway->getBackPreview('card-key');
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }

    /** @test */
    public function it_updates_a_branding_text()
    {
        list($postcardGateway, $mock) = $this->getPostcardGateway();

        $data = [
            'text' => 'text',
            'blockColor' => 'block-color',
            'textColor' => 'text-color',
        ];

        $defaultResponse = $postcardGateway->setBrandingText('card-key', $data);
        $this->assertRequest('PUT', 'api/v1/postcards/card-key/branding/text', $mock);

        $lastRequest = $mock->getLastRequest();
        $this->assertEquals($data, json_decode($lastRequest->getBody(), true));
        $this->assertEquals($defaultResponse->getCardKey(), 'card-key');

        $errorResponse = $postcardGateway->setBrandingText('card-key', $data);
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }

    /** @test */
    public function it_updates_a_branding_image()
    {
        list($postcardGateway, $mock) = $this->getPostcardGateway();

        $image = $this->createImage();

        $defaultResponse = $postcardGateway->setBrandingImage('card-key', $image);
        $this->assertRequest('PUT', 'api/v1/postcards/card-key/branding/image', $mock);
        $this->assertEquals($defaultResponse->getCardKey(), 'card-key');

        $errorResponse = $postcardGateway->setBrandingImage('card-key', $image);
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }

    /** @test */
    public function it_updates_a_branding_qr_tag()
    {
        list($postcardGateway, $mock) = $this->getPostcardGateway();

        $data = [
            'encodedText' => 'encoded-text',
            'accompanyingText' => 'accompanying-text',
            'blockColor' => 'block-color',
            'textColor' => 'text-color',
        ];

        $defaultResponse = $postcardGateway->setBrandingQrTag('card-key', $data);
        $this->assertRequest('PUT', 'api/v1/postcards/card-key/branding/qrtag', $mock);

        $lastRequest = $mock->getLastRequest();
        $this->assertEquals($data, json_decode($lastRequest->getBody(), true));
        $this->assertEquals($defaultResponse->getCardKey(), 'card-key');

        $errorResponse = $postcardGateway->setBrandingQrTag('card-key', $data);
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }

    /** @test */
    public function it_updates_a_branding_stamp()
    {
        list($postcardGateway, $mock) = $this->getPostcardGateway();

        $image = $this->createImage();

        $defaultResponse = $postcardGateway->setBrandingStamp('card-key', $image);
        $this->assertRequest('PUT', 'api/v1/postcards/card-key/branding/stamp', $mock);

        $lastRequest = $mock->getLastRequest();
        $this->assertStringContainsString('name="stamp"; filename="dummy.jpg"', $lastRequest->getBody()->getContents());
        $this->assertEquals($defaultResponse->getCardKey(), 'card-key');

        $errorResponse = $postcardGateway->setBrandingStamp('card-key', $image);
        $this->assertEquals(500, $errorResponse->getCode());
        $this->assertEquals('Internal Server Error', $errorResponse->getMessage());
    }

    protected function assertRequest($method, $url, $mock)
    {
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals($lastRequest->getHeaderLine('Authorization'), 'Bearer access-token');
        $this->assertEquals($lastRequest->getHeaderLine('Accept'), 'application/json');

        $this->assertEquals('https', $lastRequest->getUri()->getScheme());
        $this->assertEquals('www.example.org', $lastRequest->getUri()->getHost());
        $this->assertEquals($method, $lastRequest->getMethod());
        $this->assertEquals($url, ltrim($lastRequest->getRequestTarget(), '/'));
    }

    protected function getPostcardGateway($mock = null)
    {
        $mock = $mock ?? new MockHandler([
            new Response(200, [], '{"cardKey":"card-key","successMessage":"success","errors":[],"warnings":[]}'),
            new Response(500, [], '{"error":"Internal Server Error"}')
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client([
            'base_uri' => 'https://www.example.org',
            'handler' => $handlerStack,
        ]);

        $authenticationResponse = new AuthenticationResponse();
        $authenticationResponse->setAccessToken('access-token');

        $postcardGateway = new PostcardGateway($client, $authenticationResponse, 'client-id', 'client-secret');

        return [$postcardGateway, $mock];
    }

    protected function createImage()
    {
        $image = implode(DIRECTORY_SEPARATOR, [sys_get_temp_dir(), 'dummy.jpg']);
        file_put_contents($image, '');

        return $image;
    }
}
