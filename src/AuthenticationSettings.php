<?php


namespace Lifo\CiscoISE;


class AuthenticationSettings extends AbstractObject
{
    protected ?string $networkProtocol             = null;
    protected ?string $radiusSharedSecret          = null;
    protected ?string $keyEncryptionKey            = null;
    protected ?string $messageAuthenticatorCodeKey = null;
    protected ?string $keyInputFormat              = null;
    protected bool    $enableKeyWrap               = false;
    protected bool    $dtlsRequired                = false;
    protected bool    $enableMultiSecret           = false;

    /**
     * @return string|null
     */
    public function getNetworkProtocol(): ?string
    {
        return $this->networkProtocol;
    }

    /**
     * @param string|null $networkProtocol
     *
     * @return AuthenticationSettings
     */
    public function setNetworkProtocol(?string $networkProtocol): AuthenticationSettings
    {
        $this->networkProtocol = $networkProtocol;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRadiusSharedSecret(): ?string
    {
        return $this->radiusSharedSecret;
    }

    /**
     * @param string|null $radiusSharedSecret
     *
     * @return AuthenticationSettings
     */
    public function setRadiusSharedSecret(?string $radiusSharedSecret): AuthenticationSettings
    {
        $this->radiusSharedSecret = $radiusSharedSecret;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getKeyEncryptionKey(): ?string
    {
        return $this->keyEncryptionKey;
    }

    /**
     * @param string|null $keyEncryptionKey
     *
     * @return AuthenticationSettings
     */
    public function setKeyEncryptionKey(?string $keyEncryptionKey): AuthenticationSettings
    {
        $this->keyEncryptionKey = $keyEncryptionKey;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessageAuthenticatorCodeKey(): ?string
    {
        return $this->messageAuthenticatorCodeKey;
    }

    /**
     * @param string|null $messageAuthenticatorCodeKey
     *
     * @return AuthenticationSettings
     */
    public function setMessageAuthenticatorCodeKey(?string $messageAuthenticatorCodeKey): AuthenticationSettings
    {
        $this->messageAuthenticatorCodeKey = $messageAuthenticatorCodeKey;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getKeyInputFormat(): ?string
    {
        return $this->keyInputFormat;
    }

    /**
     * @param string|null $keyInputFormat
     *
     * @return AuthenticationSettings
     */
    public function setKeyInputFormat(?string $keyInputFormat): AuthenticationSettings
    {
        $this->keyInputFormat = $keyInputFormat;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableKeyWrap(): bool
    {
        return $this->enableKeyWrap;
    }

    /**
     * @param bool $enableKeyWrap
     *
     * @return AuthenticationSettings
     */
    public function setEnableKeyWrap(bool $enableKeyWrap): AuthenticationSettings
    {
        $this->enableKeyWrap = $enableKeyWrap;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDtlsRequired(): bool
    {
        return $this->dtlsRequired;
    }

    /**
     * @param bool $dtlsRequired
     *
     * @return AuthenticationSettings
     */
    public function setDtlsRequired(bool $dtlsRequired): AuthenticationSettings
    {
        $this->dtlsRequired = $dtlsRequired;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableMultiSecret(): bool
    {
        return $this->enableMultiSecret;
    }

    /**
     * ISE API uses a stringified 'false'|'true' for this one
     *
     * @param bool|string $enableMultiSecret
     *
     * @return AuthenticationSettings
     */
    public function setEnableMultiSecret($enableMultiSecret): AuthenticationSettings
    {
        if (is_string($enableMultiSecret)) {
            $enableMultiSecret = strtolower($enableMultiSecret) === 'true';
        }
        $this->enableMultiSecret = $enableMultiSecret;
        return $this;
    }


}