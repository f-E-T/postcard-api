<?php

namespace Fet\PostcardApi;

use Fet\PostcardApi\Contracts\PostcardGateway as PostcardContract;
use Fet\PostcardApi\Exception\PostcardException;
use Fet\PostcardApi\Postcard\Approval;
use Fet\PostcardApi\Postcard\Resource as PostcardResource;
use Fet\PostcardApi\Postcard\Image as PostcardImage;
use Fet\PostcardApi\Postcard\Branding as PostcardBranding;
use Fet\PostcardApi\Postcard\Previews;
use Fet\PostcardApi\Postcard\Stamp as PostcardStamp;
use Fet\PostcardApi\Postcard\SenderText as PostcardSenderText;
use Fet\PostcardApi\Postcard\RecipientAddress as PostcardRecipientAddress;
use Fet\PostcardApi\Postcard\SenderAddress as PostcardSenderAddress;
use Fet\PostcardApi\Postcard\State;
use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use Fet\PostcardApi\Response\Error as ErrorResponse;

class Postcard
{
    /**
     * @var PostcardContract The Postcard API gateway instance.
     */
    protected PostcardContract $postcardGateway;

    /**
     * @var AuthenticationResponse The authentication response from the API.
     */
    protected AuthenticationResponse $authenticationResponse;

    /**
     * @var string The campaign key for the Postcard API.
     */
    protected string $campaignKey;

    /**
     * @var ?PostcardResource The postcard resource instance, or null if not set.
     */
    protected ?PostcardResource $postcardResource = null;

    /**
     * @var array A list of warnings generated during the process.
     */
    protected array $warnings = [];

    /**
     * @var array A list of errors encountered during the process.
     */
    protected array $errors = [];

    /**
     * @param string $campaignKey The campaign key for the Postcard API.
     * @param PostcardContract $postcardGateway The Postcard API gateway instance.
     * @param AuthenticationResponse $authenticationResponse The authentication response from the API.
     */
    public function __construct(string $campaignKey, PostcardContract $postcardGateway, AuthenticationResponse $authenticationResponse)
    {
        $this->campaignKey = $campaignKey;
        $this->postcardGateway = $postcardGateway;
        $this->authenticationResponse = $authenticationResponse;
    }

    /**
     * Retrieves a PostcardResource instance based on the card key. If no card key is provided,
     * it will create a new resource using the Postcard API.
     *
     * @param string|null $cardKey The card key of the resource to retrieve (optional).
     * @return PostcardResource The PostcardResource instance.
     */
    public function getResource($cardKey = null): PostcardResource
    {
        if ($cardKey !== null) {
            $postcardResource = new PostcardResource();
            $postcardResource->setCardKey($cardKey);

            return $postcardResource;
        }

        if ($this->postcardResource === null) {
            $response = $this->handleResponse($this->postcardGateway->create($this->campaignKey));

            $postcardResource = new PostcardResource();
            $postcardResource->setCardKey($response->getCardKey());

            $this->postcardResource = $postcardResource;
        }

        return $this->postcardResource;
    }

    /**
     * Sets the PostcardResource instance for the current object.
     *
     * @param PostcardResource $postcardResource The PostcardResource instance to set.
     * @return void
     */
    public function setResource(PostcardResource $postcardResource): void
    {
        $this->postcardResource = $postcardResource;
    }

    /**
     * Adds an image to the postcard and returns a PostcardImage instance.
     *
     * @param string $image The path to the image file.
     * @return PostcardImage The created PostcardImage instance.
     */
    public function addImage(string $image): PostcardImage
    {
        $this->handleResponse($this->postcardGateway->setImage($this->getResource()->getCardKey(), $image));

        $postcardImage = new PostcardImage();
        $postcardImage->setPath($image);

        $this->getResource()->setImage($postcardImage);

        return $postcardImage;
    }

    /**
     * Adds sender text to the postcard and returns a PostcardSenderText instance.
     *
     * @param string $senderText The sender text to add to the postcard.
     * @return PostcardSenderText The created PostcardSenderText instance.
     */
    public function addSenderText(string $senderText): PostcardSenderText
    {
        $this->handleResponse($this->postcardGateway->setSenderText($this->getResource()->getCardKey(), $senderText));

        $postcardSenderText = new PostcardSenderText();
        $postcardSenderText->setText($senderText);

        $this->getResource()->setSenderText($postcardSenderText);

        return $postcardSenderText;
    }

    /**
     * Adds a recipient address to the postcard and returns a PostcardRecipientAddress instance.
     *
     * @param array $address The recipient address details as an associative array.
     * @return PostcardRecipientAddress The created PostcardRecipientAddress instance.
     */
    public function addRecipientAddress(array $address): PostcardRecipientAddress
    {
        $this->handleResponse($this->postcardGateway->setRecipientAddress($this->getResource()->getCardKey(), $address));

        $postcardRecipientAddress = new PostcardRecipientAddress();

        foreach ($address as $method => $value) {
            $setter = 'set' . ucfirst($method);

            if (method_exists($postcardRecipientAddress, $setter)) {
                $postcardRecipientAddress->{$setter}($value);
            }
        }

        $this->getResource()->setRecipientAddress($postcardRecipientAddress);

        return $postcardRecipientAddress;
    }

    /**
     * Adds a sender address to the postcard and returns a PostcardSenderAddress instance.
     *
     * @param array $address The sender address details as an associative array.
     * @return PostcardSenderAddress The created PostcardSenderAddress instance.
     */
    public function addSenderAddress(array $address): PostcardSenderAddress
    {
        $this->handleResponse($this->postcardGateway->setSenderAddress($this->getResource()->getCardKey(), $address));

        $postcardSenderAddress = new PostcardSenderAddress();

        foreach ($address as $method => $value) {
            $setter = 'set' . ucfirst($method);

            if (method_exists($postcardSenderAddress, $setter)) {
                $postcardSenderAddress->{$setter}($value);
            }
        }

        $this->getResource()->setSenderAddress($postcardSenderAddress);

        return $postcardSenderAddress;
    }

    /**
     * Adds a stamp image to the postcard and returns a PostcardStamp instance.
     *
     * @param string $image The path to the stamp image file.
     * @return PostcardStamp The created PostcardStamp instance.
     */
    public function addStampImage(string $image): PostcardStamp
    {
        $this->handleResponse($this->postcardGateway->setBrandingStamp($this->getResource()->getCardKey(), $image));

        $postcardStamp = new PostcardStamp();
        $postcardStamp->setPath($image);

        $this->getResource()->setStamp($postcardStamp);

        return $postcardStamp;
    }

    /**
     * Adds branding text to the postcard and returns a PostcardBranding instance.
     *
     * @param string $text The branding text to add to the postcard.
     * @return PostcardBranding The created PostcardBranding instance.
     */
    public function addBrandingText(string $text): PostcardBranding
    {
        $this->handleResponse($this->postcardGateway->setBrandingText($this->getResource()->getCardKey(), ['text' => $text]));

        $postcardBranding = new PostcardBranding();
        $postcardBranding->setText($text);

        $this->getResource()->setBranding($postcardBranding);

        return $postcardBranding;
    }

    /**
     * Adds a branding image to the postcard and returns a PostcardBranding instance.
     *
     * @param string $image The path to the branding image file.
     * @return PostcardBranding The created PostcardBranding instance.
     */
    public function addBrandingImage(string $image): PostcardBranding
    {
        $this->handleResponse($this->postcardGateway->setBrandingImage($this->getResource()->getCardKey(), $image));

        $postcardBranding = new PostcardBranding();
        $postcardBranding->setImage($image);

        $this->getResource()->setBranding($postcardBranding);

        return $postcardBranding;
    }

    /**
     * Adds a QR tag and accompanying text to the postcard and returns a PostcardBranding instance.
     *
     * @param string $text The QR tag text to be encoded.
     * @param string $accompanyingText Optional accompanying text to be displayed with the QR tag.
     * @return PostcardBranding The created PostcardBranding instance.
     */
    public function addBrandingQrTag(string $text, string $accompanyingText = ''): PostcardBranding
    {
        $data = [
            'encodedText' => $text,
            'accompanyingText' => $accompanyingText,
        ];

        $this->handleResponse($this->postcardGateway->setBrandingQrTag($this->getResource()->getCardKey(), $data));

        $postcardBranding = new PostcardBranding();
        $postcardBranding->setQrTagText($text);
        $postcardBranding->setQrTagAccompanyingText($accompanyingText);

        $this->getResource()->setBranding($postcardBranding);

        return $postcardBranding;
    }

    /**
     * Approves the postcard and returns an Approval instance.
     *
     * @return Approval The created Approval instance.
     */
    public function approve(): Approval
    {
        $this->handleResponse($this->postcardGateway->approve($this->getResource()->getCardKey()));

        if (!empty($this->getErrors())) {
            throw new PostcardException('Unable to approve the postcard, because the API returns errors.');
        }

        $postcardApproval = new Approval();
        $postcardApproval->approve();

        $this->getResource()->setApproval($postcardApproval);

        return $postcardApproval;
    }

    /**
     * Retrieves the current state of the postcard and returns a State instance.
     *
     * @return State The created State instance with the postcard's state and date.
     */
    public function getState(): State
    {
        $response = $this->handleResponse($this->postcardGateway->getState($this->getResource()->getCardKey()));

        $state = new State();
        $state->setState($response->getState()['state']);
        $state->setDate($response->getState()['date']);

        $this->getResource()->setState($state);

        return $state;
    }

    /**
     * Retrieves the front preview of the postcard and returns a Previews instance.
     *
     * @return Previews The created Previews instance with front preview details.
     */
    public function getFrontPreview(): Previews
    {
        $response = $this->handleResponse($this->postcardGateway->getFrontPreview($this->getResource()->getCardKey()));

        $previews = new Previews();
        $previews->setFileType($response->getFileType());
        $previews->setEncoding($response->getEncoding());
        $previews->setSide($response->getSide());
        $previews->setImageData($response->getImagedata());

        return $previews;
    }

    /**
     * Retrieves the back preview of the postcard and returns a Previews instance.
     *
     * @return Previews The created Previews instance with back preview details.
     */
    public function getBackPreview(): Previews
    {
        $response = $this->handleResponse($this->postcardGateway->getBackPreview($this->getResource()->getCardKey()));

        $previews = new Previews();
        $previews->setFileType($response->getFileType());
        $previews->setEncoding($response->getEncoding());
        $previews->setSide($response->getSide());
        $previews->setImageData($response->getImagedata());

        return $previews;
    }

    /**
     * Gets the warnings associated with the postcard.
     *
     * @return array An array of warnings.
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * Sets the warnings for the postcard.
     *
     * @param array $warings An array of warnings to set.
     */
    public function setWarnings(array $warings): void
    {
        $this->warnings[] = $warings;
    }

    /**
     * Gets the errors associated with the postcard.
     *
     * @return array An array of errors.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Sets the errors for the postcard.
     *
     * @param array $errors An array of errors to set.
     */
    public function setErrors(array $errors): void
    {
        $this->errors[] = $errors;
    }

    /**
     * Handles the response from the PostcardGateway and manages warnings and errors if present.
     *
     * @param mixed $response The response from the PostcardGateway.
     * @return mixed The response after handling warnings and errors.
     * @throws PostcardException If the response is an instance of ErrorResponse.
     */
    protected function handleResponse($response)
    {
        if ($response instanceof ErrorResponse) {
            throw new PostcardException($response->getMessage(), $response->getCode());
        }

        if (method_exists($response, 'getWarnings') && !empty($warnings = $response->getWarnings())) {
            $this->setWarnings($warnings);
        }

        if (method_exists($response, 'getErrors') && !empty($errors = $response->getErrors())) {
            $this->setErrors($errors);
        }

        return $response;
    }
}
