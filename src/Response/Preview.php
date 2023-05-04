<?php

namespace Fet\PostcardApi\Response;

class Preview
{
    /**
     * @var string The card key.
     */
    protected string $cardKey;

    /**
     * @var string The file type.
     */
    protected string $fileType;

    /**
     * @var string The encoding.
     */
    protected string $encoding;

    /**
     * @var string The side.
     */
    protected string $side;

    /**
     * @var string The imagedata.
     */
    protected string $imagedata;

    /**
     * @var array The errors.
     */
    protected array $errors;

    /**
     * Get the card key.
     *
     * @return string The card key.
     */
    public function getCardKey(): string
    {
        return $this->cardKey;
    }

    /**
     * Set the card key.
     *
     * @param string $cardKey The card key.
     * @return void
     */
    public function setCardKey(string $cardKey): void
    {
        $this->cardKey = $cardKey;
    }

    /**
     * Get the file type.
     *
     * @return string The file type.
     */
    public function getFileType(): string
    {
        return $this->fileType;
    }

    /**
     * Set the file type.
     *
     * @param string $fileType The file type.
     * @return void
     */
    public function setFileType(string $fileType): void
    {
        $this->fileType = $fileType;
    }

    /**
     * Get the encoding.
     *
     * @return string The encoding.
     */
    public function getEncoding(): string
    {
        return $this->encoding;
    }

    /**
     * Set the encoding.
     *
     * @param string $encoding The encoding.
     * @return void
     */
    public function setEncoding(string $encoding): void
    {
        $this->encoding = $encoding;
    }

    /**
     * Get the side.
     *
     * @return string The side.
     */
    public function getSide(): string
    {
        return $this->side;
    }

    /**
     * Set the side.
     *
     * @param string $side The side.
     * @return void
     */
    public function setSide(string $side): void
    {
        $this->side = $side;
    }

    /**
     * Get the imagedata.
     *
     * @return string The imagedata.
     */
    public function getImagedata(): string
    {
        return $this->imagedata;
    }

    /**
     * Set the imagedata.
     *
     * @param string $imagedata The imagedata.
     * @return void
     */
    public function setImagedata(string $imagedata): void
    {
        $this->imagedata = $imagedata;
    }

    /**
     * Get the errors.
     *
     * @return array The errors.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Set the errors.
     *
     * @param array $errors The errors.
     * @return void
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }
}
