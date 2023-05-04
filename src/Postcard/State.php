<?php

namespace Fet\PostcardApi\Postcard;

class State
{
    /**
     * @var string The state of the postcard.
     */
    protected string $state;

    /**
     * @var array The date of the postcard.
     */
    protected array $date;

    /**
     * Set the state of the postcard.
     *
     * @param string $state The state of the postcard.
     * @return void
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * Get the state of the postcard.
     *
     * @return string The state of the postcard.
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Set the date of the postcard.
     *
     * @param array $date The date of the postcard.
     * @return void
     */
    public function setDate(array $date): void
    {
        $this->date = $date;
    }

    /**
     * Get the date of the postcard.
     *
     * @return array The date of the postcard.
     */
    public function getDate(): array
    {
        return $this->date;
    }
}
