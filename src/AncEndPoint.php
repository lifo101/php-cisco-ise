<?php


namespace Lifo\CiscoISE;

class AncEndPoint extends AbstractObject
{
    const string CONFIG_URI      = 'config/ancendpoint';
    const string CONFIG_NAME_URI = self::CONFIG_URI . '/name';
    const string CONFIG_APPLY_URI = self::CONFIG_URI . '/apply';
    const string CONFIG_CLEAR_URI = self::CONFIG_URI . '/clear';
    const string JSON_ROOT_KEY    = 'ErsAncEndpoint';

    protected ?string $id          = null;
    protected ?string $name        = null;
    protected ?string $description = null;
    protected ?string $macAddress  = null;
    protected ?string $policyName  = null;

    public function __clone()
    {
        $this->id = null;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getMacAddress(): ?string
    {
        return $this->macAddress;
    }

    public function setMacAddress(?string $macAddress): self
    {
        $this->macAddress = $macAddress;
        return $this;
    }

    /**
     * Alias for {@link getMacAddress}
     *
     * @return string|null
     * @see getMacAddress
     */
    public function getMac(): ?string
    {
        return $this->macAddress;
    }

    /**
     * Alias for {@link setMacAddress}
     *
     * @param string|null $mac
     *
     * @return self
     * @see setMacAddress
     */
    public function setMac(?string $mac): self
    {
        return $this->setMacAddress($mac);
    }

    public function getPolicyName(): ?string
    {
        return $this->policyName;
    }

    public function setPolicyName(?string $policyName): self
    {
        $this->policyName = $policyName;
        return $this;
    }

}