<?php

namespace Fet\PostcardApi\Response;

class CampaignStatistic
{
    /**
     * @var string The campaign key.
     */
    protected string $campaignKey;

    /**
     * @var int The quota.
     */
    protected int $quota;

    /**
     * @var int The number of postcards sent.
     */
    protected int $sendPostcards;

    /**
     * @var int The number of free postcards that can still be sent.
     */
    protected int $freeToSendPostcards;

    /**
     * Get the campaign key.
     *
     * @return string The campaign key.
     */
    public function getCampaignKey(): string
    {
        return $this->campaignKey;
    }

    /**
     * Set the campaign key.
     *
     * @param string $campaignKey The campaign key.
     * @return void
     */
    public function setCampaignKey(string $campaignKey): void
    {
        $this->campaignKey = $campaignKey;
    }

    /**
     * Get the quota.
     *
     * @return int The quota.
     */
    public function getQuota(): int
    {
        return $this->quota;
    }

    /**
     * Set the quota.
     *
     * @param int $quota The quota.
     * @return void
     */
    public function setQuota(int $quota): void
    {
        $this->quota = $quota;
    }

    /**
     * Get the number of postcards sent.
     *
     * @return int The number of postcards sent.
     */
    public function getSendPostcards(): int
    {
        return $this->sendPostcards;
    }

    /**
     * Set the number of postcards sent.
     *
     * @param int $sendPostcards The number of postcards sent.
     * @return void
     */
    public function setSendPostcards(int $sendPostcards): void
    {
        $this->sendPostcards = $sendPostcards;
    }

    /**
     * Get the number of free postcards that can still be sent.
     *
     * @return int The number of free postcards that can still be sent.
     */
    public function getFreeToSendPostcards(): int
    {
        return $this->freeToSendPostcards;
    }

    /**
     * Set the number of free postcards that can still be sent.
     *
     * @param int $freeToSendPostcards The number of free postcards that can still be sent.
     * @return void
     */
    public function setFreeToSendPostcards(int $freeToSendPostcards): void
    {
        $this->freeToSendPostcards = $freeToSendPostcards;
    }
}
