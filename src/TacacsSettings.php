<?php


namespace Lifo\CiscoISE;


class TacacsSettings extends AbstractObject
{
    protected ?string $sharedSecret               = null;
    protected ?string $connectModeOptions         = null;
    protected ?string $previousSharedSecret       = null;
    protected ?int    $previousSharedSecretExpiry = null;

    public function getSharedSecret(): ?string
    {
        return $this->sharedSecret;
    }

    public function setSharedSecret(?string $sharedSecret): TacacsSettings
    {
        $this->sharedSecret = $sharedSecret;
        return $this;
    }

    public function getConnectModeOptions(): ?string
    {
        return $this->connectModeOptions;
    }

    public function setConnectModeOptions(?string $connectModeOptions): TacacsSettings
    {
        $this->connectModeOptions = $connectModeOptions;
        return $this;
    }

    public function getPreviousSharedSecret(): ?string
    {
        return $this->previousSharedSecret;
    }

    public function setPreviousSharedSecret(?string $previousSharedSecret): TacacsSettings
    {
        $this->previousSharedSecret = $previousSharedSecret;
        return $this;
    }

    public function getPreviousSharedSecretExpiry(): ?int
    {
        return $this->previousSharedSecretExpiry;
    }

    public function setPreviousSharedSecretExpiry(?int $previousSharedSecretExpiry): TacacsSettings
    {
        $this->previousSharedSecretExpiry = $previousSharedSecretExpiry;
        return $this;
    }

}