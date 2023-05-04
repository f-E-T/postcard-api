<?php

namespace Fet\PostcardApi\Response;

class Authentication
{
    /**
     * @var string The error.
     */
    protected string $error = '';

    /**
     * @var string The error description.
     */
    protected string $errorDescription = '';

    /**
     * @var string The access token.
     */
    protected string $accessToken = '';

    /**
     * @var string The token type.
     */
    protected string $tokenType = '';

    /**
     * @var int The number of seconds until the token expires.
     */
    protected int $expiresIn = 0;

    /**
     * Sets the error.
     *
     * @param string $error The error.
     * @return void
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }

    /**
     * Gets the error.
     *
     * @return string The error.
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * Sets the error description.
     *
     * @param string $errorDescription The error description.
     * @return void
     */
    public function setErrorDescription(string $errorDescription): void
    {
        $this->errorDescription = $errorDescription;
    }

    /**
     * Gets the error description.
     *
     * @return string The error description.
     */
    public function getErrorDescription(): string
    {
        return $this->errorDescription;
    }

    /**
     * Determines if the response is an error.
     *
     * @return bool True if an error, otherwise false.
     */
    public function isError(): bool
    {
        return !empty($this->error) || empty($this->accessToken);
    }

    /**
     * Sets the access token.
     *
     * @param string $accessToken The access token.
     * @return void
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Gets the access token.
     *
     * @return string The access token.
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * Sets the token type.
     *
     * @param string $tokenType The token type.
     * @return void
     */
    public function setTokenType(string $tokenType): void
    {
        $this->tokenType = $tokenType;
    }

    /**
     * Gets the token type.
     *
     * @return string The token type.
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * Sets the number of seconds until the token expires.
     *
     * @param int $expiresIn The number of seconds until the token expires.
     * @return void
     */
    public function setExpiresIn(int $expiresIn): void
    {
        $this->expiresIn = $expiresIn;
    }

    /**
     * Gets the number of seconds until the token expires.
     *
     * @return int The number of seconds until the token expires.
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }
}
