<?php

namespace Fet\PostcardApi\Postcard;

class SenderText
{
    /**
     * @var string The sender text.
     */
    protected string $text;

    /**
     * Set the sender text.
     *
     * @param string $text The sender text.
     * @return void
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * Get the sender text.
     *
     * @return string The sender text.
     */
    public function getText(): string
    {
        return $this->text;
    }
}
