<?php


namespace Lifo\CiscoISE;

class NetworkDeviceGroup extends AbstractObject
{
    const CONFIG_URI      = 'config/networkdevicegroup';
    const CONFIG_NAME_URI = self::CONFIG_URI . '/name';
    const JSON_ROOT_KEY   = 'NetworkDeviceGroup';

    protected ?string $id          = null;
    protected ?string $name        = null;
    protected ?string $description = null;
    protected ?string $othername   = null;

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
    public function getOthername(): ?string
    {
        return $this->othername;
    }

    /**
     * @param string|null $othername
     *
     * @return NetworkDeviceGroup
     */
    public function setOthername(?string $othername): self
    {
        $this->othername = $othername;
        return $this;
    }

}