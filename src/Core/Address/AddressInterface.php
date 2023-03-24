<?php

namespace Shop\Core\Address;

interface AddressInterface
{
    /**
     * Get sender/recipient name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Get first street line.
     *
     * @return string|null
     */
    public function getStreetLine1(): ?string;

    /**
     * Get second street line.
     *
     * @return string|null
     */
    public function getStreetLine2(): ?string;

    /**
     * Get area zipcode.
     *
     * @return string|null
     */
    public function getZipCode(): ?string;

    /**
     * Get city name.
     *
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * Get country name.
     *
     * @return string|null
     */
    public function getCountry(): ?string;

    /**
     * Get country ISO 2-char-code.
     *
     * @return string|null
     */
    public function getCountryIso2(): ?string;
}
