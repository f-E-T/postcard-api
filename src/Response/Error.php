<?php

namespace Fet\PostcardApi\Response;

class Error
{
    /**
     * @var int The error code.
     */
    protected int $code;

    /**
     * @var string The error message.
     */
    protected string $message;

    /**
     * Gets the error code.
     *
     * @return int The error code.
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Sets the error code.
     *
     * @param int $code The error code.
     * @return void
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    /**
     * Gets the error message.
     *
     * @return string The error message.
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Sets the error message.
     *
     * @param string $message The error message.
     * @return void
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
