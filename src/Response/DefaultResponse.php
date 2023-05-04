<?php

namespace Fet\PostcardApi\Response;

class DefaultResponse
{
    /**
     * @var string The card key.
     */
    protected string $cardKey;

    /**
     * @var string The success message.
     */
    protected string $successMessage;

    /**
     * @var array The errors.
     */
    protected array $errors;

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
     * Get the success message.
     *
     * @return string The success message.
     */
    public function getSuccessMessage(): string
    {
        return $this->successMessage;
    }

    /**
     * Set the success message.
     *
     * @param string $successMessage The success message.
     * @return void
     */
    public function setSuccessMessage(string $successMessage): void
    {
        $this->successMessage = $successMessage;
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
