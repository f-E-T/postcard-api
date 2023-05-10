<?php

use Fet\PostcardApi\Postcard;
use PHPUnit\Framework\TestCase;
use Fet\PostcardApi\Response\DefaultResponse;
use Fet\PostcardApi\Response\Error as ErrorResponse;
use Fet\PostcardApi\Postcard\Image as PostcardImage;
use Fet\PostcardApi\Postcard\Stamp as PostcardStamp;
use Fet\PostcardApi\Postcard\Resource as PostcardResource;
use Fet\PostcardApi\Postcard\SenderText as PostcardSenderText;
use Fet\PostcardApi\Contracts\PostcardGateway as PostcardContract;
use Fet\PostcardApi\Exception\PostcardException;
use Fet\PostcardApi\Postcard\Approval;
use Fet\PostcardApi\Postcard\Previews;
use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use Fet\PostcardApi\Postcard\RecipientAddress as PostcardRecipientAddress;
use Fet\PostcardApi\Postcard\SenderAddress as PostcardSenderAddress;
use Fet\PostcardApi\Postcard\State;
use Fet\PostcardApi\Response\PostcardState as PostcardStateResponse;
use Fet\PostcardApi\Response\Preview as PreviewResponse;

class PostcardTest extends TestCase
{
    /** @test */
    public function it_returns_a_postcard_resource()
    {
        $authenticationResponse = $this->getAuthenticationResponse();
        $defaultResponse = $this->getDefaultResponse();

        // valid data
        $gateway = $this->getMock(PostcardContract::class, 'create', $defaultResponse, ['campaign-key'], false);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->assertInstanceOf(PostcardResource::class, $postcard->getResource());
        $this->assertEquals('card-key', $postcard->getResource()->getCardKey());
        $this->assertEquals('card-key-2', $postcard->getResource('card-key-2')->getCardKey());

        // invalid data
        $gateway = $this->getMock(PostcardContract::class, 'create', Mockery::spy(ErrorResponse::class), ['campaign-key'], false);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->expectException(PostcardException::class);
        $postcard->getResource();
    }

    /** @test */
    public function it_adds_an_image()
    {
        $authenticationResponse = $this->getAuthenticationResponse();

        // valid data
        $gateway = $this->getMock(PostcardContract::class, 'setImage', Mockery::spy(DefaultResponse::class), ['card-key', 'path-to-image']);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->assertInstanceOf(PostcardImage::class, $postcard->addImage('path-to-image'));
        $this->assertEquals('path-to-image', $postcard->getResource()->getImage()->getPath());

        // invalid data
        $gateway = $this->getMock(PostcardContract::class, 'setImage', Mockery::spy(ErrorResponse::class), ['card-key', 'path-to-image']);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->expectException(PostcardException::class);
        $postcard->addImage('path-to-image');
    }

    /** @test */
    public function it_adds_a_recipient_address()
    {
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
            // this is commented out to test the default value
            // 'additionalAdrInfo' => 'additionalAdrInfo',
        ];

        $authenticationResponse = $this->getAuthenticationResponse();

        // valid data
        $gateway = $this->getMock(PostcardContract::class, 'setRecipientAddress', Mockery::spy(DefaultResponse::class), ['card-key', $data]);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);
        $postcardRecipient = $postcard->addRecipientAddress($data);

        $this->assertInstanceOf(PostcardRecipientAddress::class, $postcardRecipient);
        $this->assertEquals('title', $postcardRecipient->getTitle());
        $this->assertEquals('title', $postcard->getResource()->getRecipientAddress()->getTitle());
        $this->assertEquals('firstname', $postcardRecipient->getFirstname());
        $this->assertEquals('', $postcardRecipient->getAdditionalAdrInfo());

        // invalid data
        $data = [];
        $gateway = $this->getMock(PostcardContract::class, 'setRecipientAddress', Mockery::spy(ErrorResponse::class), ['card-key', $data]);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->expectException(PostcardException::class);
        $postcard->addRecipientAddress($data);
    }

    /** @test */
    public function it_adds_a_sender_address()
    {
        $data = [
            'lastname' => 'lastname',
            'firstname' => 'firstname',
            'company' => 'company',
            'street' => 'street',
            'houseNr' => 'houseNr',
            'zip' => 'zip',
            // this is commented out to test the default value
            // 'city' => 'city',
        ];

        $authenticationResponse = $this->getAuthenticationResponse();

        // valid data
        $gateway = $this->getMock(PostcardContract::class, 'setSenderAddress', Mockery::spy(DefaultResponse::class), ['card-key', $data]);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);
        $postcardSenderAddress = $postcard->addSenderAddress($data);

        $this->assertInstanceOf(PostcardSenderAddress::class, $postcardSenderAddress);
        $this->assertEquals('firstname', $postcardSenderAddress->getFirstname());
        $this->assertEquals('firstname', $postcard->getResource()->getSenderAddress()->getFirstname());
        $this->assertEquals('street', $postcardSenderAddress->getStreet());
        $this->assertEquals('', $postcardSenderAddress->getCity());

        // invalid data
        $data = [];
        $gateway = $this->getMock(PostcardContract::class, 'setSenderAddress', Mockery::spy(ErrorResponse::class), ['card-key', $data]);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->expectException(PostcardException::class);
        $postcard->addSenderAddress($data);
    }

    /** @test */
    public function it_adds_a_sender_text()
    {
        $authenticationResponse = $this->getAuthenticationResponse();

        // valid data
        $gateway = $this->getMock(PostcardContract::class, 'setSenderText', Mockery::spy(DefaultResponse::class), ['card-key', 'sender-text']);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);
        $postcardSenderText = $postcard->addSenderText('sender-text');

        $this->assertInstanceOf(PostcardSenderText::class, $postcardSenderText);
        $this->assertEquals('sender-text', $postcardSenderText->getText());
        $this->assertEquals('sender-text', $postcard->getResource()->getSenderText()->getText());

        // invalid data
        $gateway = $this->getMock(PostcardContract::class, 'setSenderText', Mockery::spy(ErrorResponse::class), ['card-key', 'sender-text']);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->expectException(PostcardException::class);
        $postcardSenderText = $postcard->addSenderText('sender-text');
    }

    /** @test */
    public function it_adds_a_stamp_image()
    {
        $authenticationResponse = $this->getAuthenticationResponse();

        // valid data
        $gateway = $this->getMock(PostcardContract::class, 'setBrandingStamp', Mockery::spy(DefaultResponse::class), ['card-key', 'stamp-image']);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);
        $postcardStampImage = $postcard->addStampImage('stamp-image');

        $this->assertInstanceOf(PostcardStamp::class, $postcardStampImage);
        $this->assertEquals('stamp-image', $postcardStampImage->getPath());
        $this->assertEquals('stamp-image', $postcard->getResource()->getStamp()->getPath());

        // invalid data
        $gateway = $this->getMock(PostcardContract::class, 'setBrandingStamp', Mockery::spy(ErrorResponse::class), ['card-key', 'stamp-image']);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->expectException(PostcardException::class);
        $postcard->addStampImage('stamp-image');
    }

    /** @test */
    public function it_adds_a_branding_text()
    {
        $authenticationResponse = $this->getAuthenticationResponse();

        $data = ['text' => 'text'];

        $gateway = $this->getMock(PostcardContract::class, 'setBrandingText', Mockery::spy(DefaultResponse::class), ['card-key', $data]);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);
        $postcardBranding = $postcard->addBrandingText('text');

        $this->assertEquals('text', $postcardBranding->getText());
        $this->assertEquals('text', $postcard->getResource()->getBranding()->getText());
    }

    /** @test */
    public function it_adds_a_branding_image()
    {
        $authenticationResponse = $this->getAuthenticationResponse();

        $gateway = $this->getMock(PostcardContract::class, 'setBrandingImage', Mockery::spy(DefaultResponse::class), ['card-key', 'branding-image']);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);
        $postcardBranding = $postcard->addBrandingImage('branding-image');

        $this->assertEquals('branding-image', $postcardBranding->getImage());
        $this->assertEquals('branding-image', $postcard->getResource()->getBranding()->getImage());
    }

    /** @test */
    public function it_adds_a_branding_qr_tag()
    {
        $authenticationResponse = $this->getAuthenticationResponse();

        $data = [
            'encodedText' => 'encoded-text',
            'accompanyingText' => 'accompanying-text',
        ];

        $gateway = $this->getMock(PostcardContract::class, 'setBrandingQrTag', Mockery::spy(DefaultResponse::class), ['card-key', $data]);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);
        $postcardBranding = $postcard->addBrandingQrTag('encoded-text', 'accompanying-text');

        $this->assertEquals('encoded-text', $postcardBranding->getQrTagText());
        $this->assertEquals('accompanying-text', $postcardBranding->getQrTagAccompanyingText());
        $this->assertEquals('encoded-text', $postcard->getResource()->getBranding()->getQrTagText());
        $this->assertEquals('accompanying-text', $postcard->getResource()->getBranding()->getQrTagAccompanyingText());

        $data = [
            'encodedText' => 'encoded-text',
            'accompanyingText' => '',
        ];

        $gateway = $this->getMock(PostcardContract::class, 'setBrandingQrTag', Mockery::spy(DefaultResponse::class), ['card-key', $data]);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);
        $postcardBranding = $postcard->addBrandingQrTag('encoded-text');

        $this->assertEquals('encoded-text', $postcardBranding->getQrTagText());
        $this->assertEquals('', $postcardBranding->getQrTagAccompanyingText());
    }

    /** @test */
    public function it_approves_a_postcard_resource()
    {
        $authenticationResponse = $this->getAuthenticationResponse();

        // valid data
        $gateway = $this->getMock(PostcardContract::class, 'approve', Mockery::spy(DefaultResponse::class), ['card-key']);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->assertFalse($postcard->getResource()->getApproval()->isApproved());
        $postcardApproval = $postcard->approve();
        $this->assertInstanceOf(Approval::class, $postcardApproval);
        $this->assertTrue($postcard->getResource()->getApproval()->isApproved());

        // invalid data
        $gateway = $this->getMock(PostcardContract::class, 'approve', Mockery::spy(ErrorResponse::class), ['card-key']);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->expectException(PostcardException::class);
        $postcard->approve();
    }

    /** @test */
    public function it_cannot_approve_a_postcard_resource_when_the_api_returns_errors()
    {
        $authenticationResponse = $this->getAuthenticationResponse();
        
        $defaultResponse = $this->getDefaultResponse();
        $defaultResponse->setErrors([
            [
                'code' => 'code1',
                'description' => 'description1',
            ],
        ]);
        $gateway = $this->getMock(PostcardContract::class, 'approve', $defaultResponse, ['card-key']);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->expectException(PostcardException::class);
        $postcard->approve();
    }

    /** @test */
    public function it_returns_the_current_state()
    {
        $postcardStateResponse = new PostcardStateResponse();
        $postcardStateResponse->setState(['state' => 'state', 'date' => ['1970', '1', '1']]);
        $postcardStateResponse->setWarnings([]);

        $authenticationResponse = $this->getAuthenticationResponse();

        // valid data
        $gateway = $this->getMock(PostcardContract::class, 'getState', $postcardStateResponse);
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->assertInstanceOf(State::class, $postcard->getState());
        $this->assertEquals('state', $postcard->getState()->getState());
        $this->assertEquals(['1970', '1', '1'], $postcard->getState()->getDate());
        $this->assertEquals('state', $postcard->getResource()->getState()->getState());
        $this->assertEquals(['1970', '1', '1'], $postcard->getResource()->getState()->getDate());

        // invalid data
        $gateway = $this->getMock(PostcardContract::class, 'getState', Mockery::spy(ErrorResponse::class));
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->expectException(PostcardException::class);
        $postcard->getState();
    }

    /** @test */
    public function it_returns_the_front_preview()
    {
        $authenticationResponse = $this->getAuthenticationResponse();

        // valid data
        $gateway = $this->getMock(PostcardContract::class, 'getFrontPreview', Mockery::spy(PreviewResponse::class));
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->assertInstanceOf(Previews::class, $postcard->getFrontPreview());

        // invalid data
        $gateway = $this->getMock(PostcardContract::class, 'getFrontPreview', Mockery::spy(ErrorResponse::class));
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->expectException(PostcardException::class);
        $postcard->getFrontPreview();
    }

    /** @test */
    public function it_returns_the_back_preview()
    {
        $authenticationResponse = $this->getAuthenticationResponse();

        // valid dataa
        $gateway = $this->getMock(PostcardContract::class, 'getBackPreview', Mockery::spy(PreviewResponse::class));
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->assertInstanceOf(Previews::class, $postcard->getBackPreview());

        // invalid data
        $gateway = $this->getMock(PostcardContract::class, 'getBackPreview', Mockery::spy(ErrorResponse::class));
        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);

        $this->expectException(PostcardException::class);
        $postcard->getBackPreview();
    }

    /** @test */
    public function it_collects_warnings()
    {
        $authenticationResponse = $this->getAuthenticationResponse();
        $warnings1 = [
            [
                'code' => 'code1',
                'description' => 'description1',
            ],
            [
                'code' => 'code2',
                'description' => 'description2',
            ]
        ];

        $warnings2 = [
            [
                'code' => 'code1',
                'description' => 'description1',
            ]
        ];

        $defaultResponse1 = new DefaultResponse();
        $defaultResponse1->setCardKey('card-key');
        $defaultResponse1->setErrors([]);
        $defaultResponse1->setWarnings($warnings1);

        $defaultResponse2 = new DefaultResponse();
        $defaultResponse2->setCardKey('card-key');
        $defaultResponse2->setErrors([]);
        $defaultResponse2->setWarnings($warnings2);

        $defaultResponse3 = new DefaultResponse();
        $defaultResponse3->setCardKey('card-key');
        $defaultResponse3->setErrors([]);
        $defaultResponse3->setWarnings([]);

        $gateway = Mockery::mock(PostcardContract::class);
        $gateway
            ->shouldReceive('create')
            ->andReturn($defaultResponse1);

        $gateway
            ->shouldReceive('setSenderText')
            ->andReturn($defaultResponse2);

        $gateway
            ->shouldReceive('setImage')
            ->andReturn($defaultResponse3);

        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);
        $postcard->getResource();
        $postcard->addSenderText('sender-text');
        $postcard->addImage('path-to-image');
        $this->assertEquals([$warnings1, $warnings2], $postcard->getWarnings());
    }

    /** @test */
    public function it_collects_errors()
    {
        $authenticationResponse = $this->getAuthenticationResponse();
        $errors1 = [
            [
                'code' => 'code1',
                'description' => 'description1',
            ],
            [
                'code' => 'code2',
                'description' => 'description2',
            ]
        ];

        $errors2 = [
            [
                'code' => 'code1',
                'description' => 'description1',
            ]
        ];

        $defaultResponse1 = new DefaultResponse();
        $defaultResponse1->setCardKey('card-key');
        $defaultResponse1->setWarnings([]);
        $defaultResponse1->setErrors($errors1);

        $defaultResponse2 = new DefaultResponse();
        $defaultResponse2->setCardKey('card-key');
        $defaultResponse2->setWarnings([]);
        $defaultResponse2->setErrors($errors2);

        $defaultResponse3 = new DefaultResponse();
        $defaultResponse3->setCardKey('card-key');
        $defaultResponse3->setWarnings([]);
        $defaultResponse3->setErrors([]);

        $gateway = Mockery::mock(PostcardContract::class);
        $gateway
            ->shouldReceive('create')
            ->andReturn($defaultResponse1);

        $gateway
            ->shouldReceive('setSenderText')
            ->andReturn($defaultResponse2);

        $gateway
            ->shouldReceive('setImage')
            ->andReturn($defaultResponse3);

        $postcard = new Postcard('campaign-key', $gateway, $authenticationResponse);
        $postcard->getResource();
        $postcard->addSenderText('sender-text');
        $postcard->addImage('path-to-image');
        $this->assertEquals([$errors1, $errors2], $postcard->getErrors());
    }

    protected function getDefaultResponse()
    {
        $defaultResponse = new DefaultResponse();
        $defaultResponse->setCardKey('card-key');
        $defaultResponse->setWarnings([]);
        $defaultResponse->setErrors([]);

        return $defaultResponse;
    }

    protected function getAuthenticationResponse()
    {
        $authenticationResponse = new AuthenticationResponse();
        $authenticationResponse->setAccessToken('access-token');

        return $authenticationResponse;
    }

    protected function getMock(string $class, string $method, $response, array $args = [], $create = true)
    {
        $gateway = Mockery::mock($class);
        $mock = $gateway->shouldReceive($method);

        if (!empty($args)) {
            $mock->withArgs($args);
        }

        $mock->andReturn($response);

        if ($create) {
            $gateway
                ->shouldReceive('create')
                ->andReturn($this->getDefaultResponse());
        }

        return $gateway;
    }
}
