<?php

namespace Fet\PostcardApi\Contracts;

use Fet\PostcardApi\Response\Authentication as AuthenticationResponse;
use Fet\PostcardApi\Response\Error as ErrorResponse;

interface Authentication
{
    public function authenticate(): AuthenticationResponse|ErrorResponse;
}
