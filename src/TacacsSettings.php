<?php


namespace Lifo\CiscoISE;


class TacacsSettings extends AbstractObject
{
    protected ?string $sharedSecret               = null;
    protected ?string $connectModeOptions         = null;
    protected ?string $previousSharedSecret       = null;
    protected ?int    $previousSharedSecretExpiry = null;

    /**
     * @return string|null
     */
    public function getSharedSecret(): ?string
    {
        return $this->sharedSecret;
    }

    /**
     * @param string|null $sharedSecret
     *
     * @return TacacsSettings
     */
    public function setSharedSecret(?string $sharedSecret): TacacsSettings
    {
        $this->sharedSecret = $sharedSecret;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getConnectModeOptions(): ?string
    {
        return $this->connectModeOptions;
    }

    /**
     * @param string|null $connectModeOptions
     *
     * @return TacacsSettings
     */
    public function setConnectModeOptions(?string $connectModeOptions): TacacsSettings
    {
        $this->connectModeOptions = $connectModeOptions;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPreviousSharedSecret(): ?string
    {
        return $this->previousSharedSecret;
    }

    /**
     * @param string|null $previousSharedSecret
     *
     * @return TacacsSettings
     */
    public function setPreviousSharedSecret(?string $previousSharedSecret): TacacsSettings
    {
        $this->previousSharedSecret = $previousSharedSecret;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPreviousSharedSecretExpiry(): ?int
    {
        return $this->previousSharedSecretExpiry;
    }

    /**
     * @param int|null $previousSharedSecretExpiry
     *
     * @return TacacsSettings
     */
    public function setPreviousSharedSecretExpiry(?int $previousSharedSecretExpiry): TacacsSettings
    {
        $this->previousSharedSecretExpiry = $previousSharedSecretExpiry;
        return $this;
    }

}