<?php

namespace Fet\PostcardApi\Postcard;

use Fet\PostcardApi\Postcard\Image;
use Fet\PostcardApi\Postcard\State;
use Fet\PostcardApi\Postcard\Approval;
use Fet\PostcardApi\Postcard\Branding;
use Fet\PostcardApi\Postcard\Previews;
use Fet\PostcardApi\Postcard\SenderText;
use Fet\PostcardApi\Postcard\SenderAddress;
use Fet\PostcardApi\Postcard\RecipientAddress;
use Fet\PostcardApi\Postcard\Stamp;

class Resource
{
    protected SenderAddress $senderAddress;
    protected RecipientAddress $recipientAddress;
    protected Previews $previews;
    protected Branding $branding;
    protected SenderText $senderText;
    protected Image $image;
    protected Approval $approval;
    protected State $state;
    protected Stamp $stamp;
    protected string $cardKey;

    public function __construct()
    {
        $this->approval = new Approval();
    }

    public function setSenderAddress(SenderAddress $senderAddress): void
    {
        $this->senderAddress = $senderAddress;
    }

    public function getSenderAddress(): SenderAddress
    {
        return $this->senderAddress;
    }

    public function setRecipientAddress(RecipientAddress $recipientAddress): void
    {
        $this->recipientAddress = $recipientAddress;
    }

    public function getRecipientAddress(): RecipientAddress
    {
        return $this->recipientAddress;
    }

    public function setPreviews(Previews $previews): void
    {
        $this->previews = $previews;
    }

    public function getPreviews(): Previews
    {
        return $this->previews;
    }

    public function setBranding(Branding $branding): void
    {
        $this->branding = $branding;
    }

    public function getBranding(): Branding
    {
        return $this->branding;
    }

    public function setSenderText(SenderText $senderText): void
    {
        $this->senderText = $senderText;
    }

    public function getSenderText(): SenderText
    {
        return $this->senderText;
    }

    public function setImage(Image $image): void
    {
        $this->image = $image;
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    public function setApproval(Approval $approval): void
    {
        $this->approval = $approval;
    }

    public function getApproval(): Approval
    {
        return $this->approval;
    }

    public function setState(State $state): void
    {
        $this->state = $state;
    }

    public function getState(): State
    {
        return $this->state;
    }

    public function setStamp(Stamp $stamp): void
    {
        $this->stamp = $stamp;
    }

    public function getStamp(): Stamp
    {
        return $this->stamp;
    }

    public function setCardKey(string $cardKey): void
    {
        $this->cardKey = $cardKey;
    }

    public function getCardKey(): string
    {
        return $this->cardKey;
    }
}
