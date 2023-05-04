<?php

namespace Fet\PostcardApi\Postcard;

class Previews
{
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
     * @var string The image data.
     */
    protected string $imageData;

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
     * Get the file type.
     *
     * @return string The file type.
     */
    public function getFileType(): string
    {
        return $this->fileType;
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
     * Get the encoding.
     *
     * @return string The encoding.
     */
    public function getEncoding(): string
    {
        return $this->encoding;
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
     * Get the side.
     *
     * @return string The side.
     */
    public function getSide(): string
    {
        return $this->side;
    }

    /**
     * Set the image data.
     *
     * @param string $imageData The image data.
     * @return void
     */
    public function setImageData(string $imageData): void
    {
        $this->imageData = $imageData;
    }

    /**
     * Get the image data.
     *
     * @return string The image data.
     */
    public function getImageData(): string
    {
        return $this->imageData;
    }
}
