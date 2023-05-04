<?php

namespace Fet\PostcardApi\Postcard;

class Image
{
    /**
     * @var string The file path to the image.
     */
    protected string $path;

    /**
     * Get the file path to the image.
     *
     * @return string The file path to the image.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set the file path to the image.
     *
     * @param string $path The file path to the image.
     * @return void
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}
