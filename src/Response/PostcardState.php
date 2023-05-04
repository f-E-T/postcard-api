<?php

namespace Fet\PostcardApi\Response;

class PostcardState
{
    /**
     * @var string The card key.
     */
    protected string $cardKey;

    /**
     * @var array The state.
     */
    protected array $state;

    /**
     * @var array The warnings.
     */
    protected array $warnings;

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
     * Get the state.
     *
     * @return array The state.
     */
    public function getState(): array
    {
        return $this->state;
    }

    /**
     * Set the state.
     *
     * @param array $state The state.
     * @return void
     */
    public function setState(array $state): void
    {
        $this->state = $state;
    }

    /**
     * Get the warnings.
     *
     * @return array The warnings.
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * Set the warnings.
     *
     * @param array $warnings The warnings.
     * @return void
     */
    public function setWarnings(array $warnings): void
    {
        $this->warnings = $warnings;
    }
}
