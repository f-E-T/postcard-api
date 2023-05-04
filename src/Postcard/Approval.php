<?php

namespace Fet\PostcardApi\Postcard;

class Approval
{
    /**
     * @var bool Whether the postcard is approved or not.
     */
    protected bool $approved = false;

    /**
     * Check if the postcard is approved.
     *
     * @return bool Whether the postcard is approved.
     */
    public function isApproved(): bool
    {
        return $this->approved;
    }

    /**
     * Set the postcard as approved.
     *
     * @return void
     */
    public function approve(): void
    {
        $this->approved = true;
    }
}
