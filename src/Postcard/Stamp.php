<?php

namespace Fet\PostcardApi\Postcard;

class Stamp
{
    /**
     * @var string The path to the stamp image.
     */
    protected string $path;

    /**
     * Get the path to the stamp image.
     *
     * @return string The path to the stamp image.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set the path to the stamp image.
     *
     * @param string $path The path to the stamp image.
     * @return void
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}
