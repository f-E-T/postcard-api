<?php

namespace Fet\PostcardApi;

use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use Fet\PostcardApi\Contracts\Authentication as AuthenticationContract;
use Fet\PostcardApi\Exception\AuthenticationException;

class Authentication
{
    /**
     * @var AuthenticationContract The authentication gateway.
     */
    protected AuthenticationContract $gateway;

    /**
     * @var bool Indicates whether the user is authenticated.
     */
    protected bool $authenticated = false;

    /**
     * @param AuthenticationContract $gateway The authentication gateway.
     */
    public function __construct(AuthenticationContract $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Authenticate the user by interacting with the authentication gateway.
     *
     * @return AuthenticationResponse The authentication response object.
     * @throws AuthenticationException If authentication fails.
     */
    public function authenticate(): AuthenticationResponse
    {
        $response = $this->gateway->authenticate();

        if ($response->isError()) {
            throw new AuthenticationException(sprintf(
                'Authentication failed with error: %s (%s)',
                $response->getError(),
                $response->getErrorDescription()
            ));
        }

        $this->setAuthenticated(true);

        return $response;
    }

    /**
     * Check if the user is authenticated.
     *
     * @return bool True if authenticated, false otherwise.
     */
    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    /**
     * Set the authenticated state of the user.
     *
     * @param bool $authenticated The authenticated state.
     */
    public function setAuthenticated(bool $authenticated): void
    {
        $this->authenticated = $authenticated;
    }
}
