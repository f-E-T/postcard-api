<?php

namespace Fet\PostcardApi\Postcard;

class RecipientAddress
{
    protected string $title = '';
    protected string $lastname = '';
    protected string $firstname = '';
    protected string $company = '';
    protected string $street = '';
    protected string $houseNr = '';
    protected string $zip = '';
    protected string $city = '';
    protected string $country = '';
    protected string $poBox = '';
    protected string $additionalAdrInfo = '';

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setHouseNr(string $houseNr): void
    {
        $this->houseNr = $houseNr;
    }

    public function getHouseNr(): string
    {
        return $this->houseNr;
    }

    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setPoBox(string $poBox): void
    {
        $this->poBox = $poBox;
    }

    public function getPoBox(): string
    {
        return $this->poBox;
    }

    public function setAdditionalAdrInfo(string $additionalAdrInfo): void
    {
        $this->additionalAdrInfo = $additionalAdrInfo;
    }

    public function getAdditionalAdrInfo(): string
    {
        return $this->additionalAdrInfo;
    }
}
