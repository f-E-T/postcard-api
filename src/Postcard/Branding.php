<?php

namespace Fet\PostcardApi\Postcard;

class Branding
{
    /**
     * The branding text.
     *
     * @var string
     */
    protected string $text;

    /**
     * The path to the branding image.
     *
     * @var string
     */
    protected string $image;

    /**
     * The QR tag text for the branding.
     *
     * @var string
     */
    protected string $qrTagText;

    /**
     * The accompanying text for the QR tag.
     *
     * @var string
     */
    protected string $qrTagAccompanyingText;

    /**
     * Get the branding text.
     *
     * @return string The branding text.
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Set the branding text.
     *
     * @param string $text The branding text.
     * @return void
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * Get the path to the branding image.
     *
     * @return string The path to the branding image.
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Set the path to the branding image.
     *
     * @param string $image The path to the branding image.
     * @return void
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * Get the QR tag text for the branding.
     *
     * @return string The QR tag text for the branding.
     */
    public function getQrTagText(): string
    {
        return $this->qrTagText;
    }

    /**
     * Set the QR tag text for the branding.
     *
     * @param string $qrTagText The QR tag text for the branding.
     * @return void
     */
    public function setQrTagText(string $qrTagText): void
    {
        $this->qrTagText = $qrTagText;
    }

    /**
     * Get the accompanying text for the QR tag.
     *
     * @return string The accompanying text for the QR tag.
     */
    public function getQrTagAccompanyingText(): string
    {
        return $this->qrTagAccompanyingText;
    }

    /**
     * Set the accompanying text for the QR tag.
     *
     * @param string $qrTagAccompanyingText The accompanying text for the QR tag.
     * @return void
     */
    public function setQrTagAccompanyingText(string $qrTagAccompanyingText): void
    {
        $this->qrTagAccompanyingText = $qrTagAccompanyingText;
    }
}
