<?php


namespace Lifo\CiscoISE;


class SnmpSettings extends AbstractObject
{
    protected ?string $version                       = null;
    protected ?string $username                      = null;
    protected ?string $securityLevel                 = null;
    protected ?string $authProtocol                  = null;
    protected ?string $authPassword                  = null;
    protected ?string $privacyProtocol               = null;
    protected ?string $privacyPassword               = null;
    protected ?string $roCommunity                   = null;
    protected ?string $originatingPolicyServicesNode = null;
    protected ?int    $pollingInterval               = 28800;
    protected bool    $linkTrapQuery                 = false;
    protected bool    $macTrapQuery                  = false;

    public function mapPropToKey(string $prop): string
    {
        return match ($prop) {
            // needed until ISE API fixes the bug
            'authPassword', 'privacyPassword' => str_replace('Password', 'Passowrd', $prop),
            default => parent::mapPropToKey($prop),
        };
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): SnmpSettings
    {
        switch (true) {
            case $version === null:
                break;
            case in_array(strtolower($version), ['1', 'one']):
                $version = 'ONE';
                break;
            case in_array(strtolower($version), ['2', '2c', 'two', 'two_c']):
                $version = 'TWO_C';
                break;
            case in_array(strtolower($version), ['3', 'three']):
                $version = 'THREE';
                break;
        }
        $this->version = $version;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): SnmpSettings
    {
        $this->username = $username;
        return $this;
    }

    public function getSecurityLevel(): ?string
    {
        return $this->securityLevel;
    }

    public function setSecurityLevel(?string $securityLevel): SnmpSettings
    {
        $this->securityLevel = $securityLevel;
        return $this;
    }

    public function getAuthProtocol(): ?string
    {
        return $this->authProtocol;
    }

    public function setAuthProtocol(?string $authProtocol): SnmpSettings
    {
        $this->authProtocol = $authProtocol;
        return $this;
    }

    public function getAuthPassword(): ?string
    {
        return $this->authPassword;
    }

    public function setAuthPassword(?string $authPassword): SnmpSettings
    {
        $this->authPassword = $authPassword;
        return $this;
    }

    /**
     * @param string|null $pw
     *
     * @return SnmpSettings
     * @deprecated Due to typo in ISE API (note the 'Passowrd' instead of 'Password'). Use {@link setAuthPassword} instead.
     * @internal   This is here only so the PropertyAccessor will properly set the password
     */
    public function setAuthPassowrd(?string $pw): SnmpSettings
    {
        return $this->setAuthPassword($pw);
    }

    public function getPrivacyProtocol(): ?string
    {
        return $this->privacyProtocol;
    }

    public function setPrivacyProtocol(?string $privacyProtocol): SnmpSettings
    {
        $this->privacyProtocol = $privacyProtocol;
        return $this;
    }

    public function getPrivacyPassword(): ?string
    {
        return $this->privacyPassword;
    }

    public function setPrivacyPassword(?string $privacyPassword): SnmpSettings
    {
        $this->privacyPassword = $privacyPassword;
        return $this;
    }

    /**
     *
     * @param string|null $pw
     *
     * @return SnmpSettings
     * @internal   This is here only so the PropertyAccessor will properly set the password
     * @deprecated Due to typo in ISE API (note the 'Passowrd' instead of 'Password'). Use {@link setPrivacyPassword} instead.
     */
    public function setPrivacyPassowrd(?string $pw): SnmpSettings
    {
        return $this->setPrivacyPassword($pw);
    }

    public function getRoCommunity(): ?string
    {
        return $this->roCommunity;
    }

    public function setRoCommunity(?string $roCommunity): SnmpSettings
    {
        $this->roCommunity = $roCommunity;
        return $this;
    }

    public function getOriginatingPolicyServicesNode(): ?string
    {
        return $this->originatingPolicyServicesNode;
    }

    public function setOriginatingPolicyServicesNode(?string $originatingPolicyServicesNode): SnmpSettings
    {
        $this->originatingPolicyServicesNode = $originatingPolicyServicesNode;
        return $this;
    }

    public function getPollingInterval(): ?int
    {
        return $this->pollingInterval;
    }

    public function setPollingInterval(?int $pollingInterval): SnmpSettings
    {
        $this->pollingInterval = $pollingInterval;
        return $this;
    }

    public function isLinkTrapQuery(): bool
    {
        return $this->linkTrapQuery;
    }

    public function setLinkTrapQuery(bool $linkTrapQuery): SnmpSettings
    {
        $this->linkTrapQuery = $linkTrapQuery;
        return $this;
    }

    public function isMacTrapQuery(): bool
    {
        return $this->macTrapQuery;
    }

    public function setMacTrapQuery(bool $macTrapQuery): SnmpSettings
    {
        $this->macTrapQuery = $macTrapQuery;
        return $this;
    }

}