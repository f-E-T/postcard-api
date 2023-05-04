<?php

namespace Fet\PostcardApi\Contracts;

use Fet\PostcardApi\Response\DefaultResponse;
use Fet\PostcardApi\Response\Error as ErrorResponse;
use Fet\PostcardApi\Response\PostcardState as PostcardStateResponse;
use Fet\PostcardApi\Response\Preview as PreviewResponse;

interface PostcardGateway
{
    public function create(string $campaignKey): DefaultResponse|ErrorResponse;

    public function setRecipientAddress(string $cardKey, array $data): DefaultResponse|ErrorResponse;

    public function approve(string $cardKey): DefaultResponse|ErrorResponse;

    public function setSenderAddress(string $cardKey, array $data): DefaultResponse|ErrorResponse;

    public function setImage(string $cardKey, string $image): DefaultResponse|ErrorResponse;

    public function setSenderText(string $cardKey, string $senderText): DefaultResponse|ErrorResponse;

    public function getState(string $cardKey): PostcardStateResponse|ErrorResponse;

    public function getFrontPreview(string $cardKey): PreviewResponse|ErrorResponse;

    public function getBackPreview(string $cardKey): PreviewResponse|ErrorResponse;

    public function setBrandingText(string $cardKey, array $data): DefaultResponse|ErrorResponse;

    public function setBrandingImage(string $cardKey, string $image): DefaultResponse|ErrorResponse;

    public function setBrandingQrTag(string $cardKey, array $data): DefaultResponse|ErrorResponse;

    public function setBrandingStamp(string $cardKey, string $image): DefaultResponse|ErrorResponse;
}
