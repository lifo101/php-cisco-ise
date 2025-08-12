<?php


namespace Lifo\CiscoISE;

class NetworkDeviceGroup extends AbstractObject
{
    const string CONFIG_URI      = 'config/networkdevicegroup';
    const string CONFIG_NAME_URI = self::CONFIG_URI . '/name';
    const string JSON_ROOT_KEY   = 'NetworkDeviceGroup';

    protected ?string $id          = null;
    protected ?string $name        = null;
    protected ?string $description = null;
    protected ?string $othername   = null;

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

    public function getOthername(): ?string
    {
        return $this->othername;
    }

    public function setOthername(?string $othername): self
    {
        $this->othername = $othername;
        return $this;
    }

}