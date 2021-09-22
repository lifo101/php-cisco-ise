<?php


namespace Lifo\CiscoISE;

class AncEndPoint extends AbstractObject
{
    const CONFIG_URI       = 'config/ancendpoint';
    const CONFIG_NAME_URI  = self::CONFIG_URI . '/name';
    const CONFIG_APPLY_URI = self::CONFIG_URI . '/apply';
    const CONFIG_CLEAR_URI = self::CONFIG_URI . '/clear';
    const JSON_ROOT_KEY    = 'ErsAncEndpoint';

    protected ?string $id          = null;
    protected ?string $name        = null;
    protected ?string $description = null;
    protected ?string $macAddress  = null;
    protected ?string $policyName  = null;

    public function __clone()
    {
        $this->id = null;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     *
     * @return self
     */
    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMacAddress(): ?string
    {
        return $this->macAddress;
    }

    /**
     * @param string|null $macAddress
     *
     * @return self
     */
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

    /**
     * @return string|null
     */
    public function getPolicyName(): ?string
    {
        return $this->policyName;
    }

    /**
     * @param string|null $policyName
     *
     * @return self
     */
    public function setPolicyName(?string $policyName): self
    {
        $this->policyName = $policyName;
        return $this;
    }

}