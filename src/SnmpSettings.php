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
        switch ($prop) {
            // needed until ISE API fixes the bug
            case 'authPassword':
            case 'privacyPassword':
                return str_replace('Password', 'Passowrd', $prop);
        }
        return parent::mapPropToKey($prop);
    }

    /**
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * @param string|null $version
     *
     * @return SnmpSettings
     */
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

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     *
     * @return SnmpSettings
     */
    public function setUsername(?string $username): SnmpSettings
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSecurityLevel(): ?string
    {
        return $this->securityLevel;
    }

    /**
     * @param string|null $securityLevel
     *
     * @return SnmpSettings
     */
    public function setSecurityLevel(?string $securityLevel): SnmpSettings
    {
        $this->securityLevel = $securityLevel;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthProtocol(): ?string
    {
        return $this->authProtocol;
    }

    /**
     * @param string|null $authProtocol
     *
     * @return SnmpSettings
     */
    public function setAuthProtocol(?string $authProtocol): SnmpSettings
    {
        $this->authProtocol = $authProtocol;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthPassword(): ?string
    {
        return $this->authPassword;
    }

    /**
     * @param string|null $authPassword
     *
     * @return SnmpSettings
     */
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

    /**
     * @return string|null
     */
    public function getPrivacyProtocol(): ?string
    {
        return $this->privacyProtocol;
    }

    /**
     * @param string|null $privacyProtocol
     *
     * @return SnmpSettings
     */
    public function setPrivacyProtocol(?string $privacyProtocol): SnmpSettings
    {
        $this->privacyProtocol = $privacyProtocol;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrivacyPassword(): ?string
    {
        return $this->privacyPassword;
    }

    /**
     * @param string|null $privacyPassword
     *
     * @return SnmpSettings
     */
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

    /**
     * @return string|null
     */
    public function getRoCommunity(): ?string
    {
        return $this->roCommunity;
    }

    /**
     * @param string|null $roCommunity
     *
     * @return SnmpSettings
     */
    public function setRoCommunity(?string $roCommunity): SnmpSettings
    {
        $this->roCommunity = $roCommunity;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOriginatingPolicyServicesNode(): ?string
    {
        return $this->originatingPolicyServicesNode;
    }

    /**
     * @param string|null $originatingPolicyServicesNode
     *
     * @return SnmpSettings
     */
    public function setOriginatingPolicyServicesNode(?string $originatingPolicyServicesNode): SnmpSettings
    {
        $this->originatingPolicyServicesNode = $originatingPolicyServicesNode;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPollingInterval(): ?int
    {
        return $this->pollingInterval;
    }

    /**
     * @param int|null $pollingInterval
     *
     * @return SnmpSettings
     */
    public function setPollingInterval(?int $pollingInterval): SnmpSettings
    {
        $this->pollingInterval = $pollingInterval;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLinkTrapQuery(): bool
    {
        return $this->linkTrapQuery;
    }

    /**
     * @param bool $linkTrapQuery
     *
     * @return SnmpSettings
     */
    public function setLinkTrapQuery(bool $linkTrapQuery): SnmpSettings
    {
        $this->linkTrapQuery = $linkTrapQuery;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMacTrapQuery(): bool
    {
        return $this->macTrapQuery;
    }

    /**
     * @param bool $macTrapQuery
     *
     * @return SnmpSettings
     */
    public function setMacTrapQuery(bool $macTrapQuery): SnmpSettings
    {
        $this->macTrapQuery = $macTrapQuery;
        return $this;
    }

}