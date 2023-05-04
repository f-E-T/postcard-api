<?php

use PHPUnit\Framework\TestCase;
use Fet\PostcardApi\Postcard\Resource as PostcardResource;
use Fet\PostcardApi\Postcard\Image;
use Fet\PostcardApi\Postcard\Stamp;
use Fet\PostcardApi\Postcard\State;
use Fet\PostcardApi\Postcard\Approval;
use Fet\PostcardApi\Postcard\Branding;
use Fet\PostcardApi\Postcard\Previews;
use Fet\PostcardApi\Postcard\SenderText;
use Fet\PostcardApi\Postcard\SenderAddress;
use Fet\PostcardApi\Postcard\RecipientAddress;

class ResourceTest extends TestCase
{
    /** @test */
    public function it_has_a_sender_address()
    {
        $postcard = new PostcardResource();
        $senderAddress = new SenderAddress();

        $postcard->setSenderAddress($senderAddress);

        $this->assertEquals($senderAddress, $postcard->getSenderAddress());
    }

    /** @test */
    public function it_has_a_recipient_address()
    {
        $postcard = new PostcardResource();
        $recipientAddress = new RecipientAddress();

        $postcard->setRecipientAddress($recipientAddress);

        $this->assertEquals($recipientAddress, $postcard->getRecipientAddress());
    }

    /** @test */
    public function it_has_previews()
    {
        $postcard = new PostcardResource();
        $previews = new Previews();

        $postcard->setPreviews($previews);

        $this->assertEquals($previews, $postcard->getPreviews());
    }

    /** @test */
    public function it_has_a_branding()
    {
        $postcard = new PostcardResource();
        $branding = new Branding();

        $postcard->setBranding($branding);

        $this->assertEquals($branding, $postcard->getBranding());
    }

    /** @test */
    public function it_has_a_sender_text()
    {
        $postcard = new PostcardResource();
        $senderText = new SenderText();

        $postcard->setSenderText($senderText);

        $this->assertEquals($senderText, $postcard->getSenderText());
    }

    /** @test */
    public function it_has_an_image()
    {
        $postcard = new PostcardResource();
        $image = new Image();

        $postcard->setImage($image);

        $this->assertEquals($image, $postcard->getImage());
    }

    /** @test */
    public function it_has_an_approval()
    {
        $postcard = new PostcardResource();
        $approval = new Approval();

        $postcard->setApproval($approval);

        $this->assertEquals($approval, $postcard->getApproval());
    }

    /** @test */
    public function it_has_a_state()
    {
        $postcard = new PostcardResource();
        $state = new State();

        $postcard->setState($state);

        $this->assertEquals($state, $postcard->getState());
    }

    /** @test */
    public function it_has_a_stamp()
    {
        $postcard = new PostcardResource();
        $stamp = new Stamp();

        $postcard->setStamp($stamp);

        $this->assertEquals($stamp, $postcard->getStamp());
    }

    /** @test */
    public function it_has_a_card_key()
    {
        $postcard = new PostcardResource();

        $postcard->setCardKey('card-key');

        $this->assertEquals('card-key', $postcard->getCardKey());
    }
}
