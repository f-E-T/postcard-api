<?php

namespace Fet\PostcardApi\Contracts;

use Fet\PostcardApi\Postcard\Image;
use Fet\PostcardApi\Postcard\Stamp;
use Fet\PostcardApi\Postcard\Branding;
use Fet\PostcardApi\Postcard\RecipientAddress;
use Fet\PostcardApi\Postcard\Resource as PostcardResource;
use Fet\PostcardApi\Postcard\SenderText;
use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;

interface Postcard
{
    public function createResource(string $campaignKey, AuthenticationResponse $authenticationResponse): PostcardResource;

    public function createImage(string $image, PostcardResource $postcardResource): Image;

    public function createSenderText(string $senderText, PostcardResource $postcardResource): SenderText;

    public function createRecipientAddress(array $address, PostcardResource $postcardResource): RecipientAddress;

    public function createBranding(PostcardResource $postcardResource): Branding;

    public function createStamp(string $image, PostcardResource $postcardResource): Stamp;

    public function approve(PostcardResource $postcardResource): bool;
}
