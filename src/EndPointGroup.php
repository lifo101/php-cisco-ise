<?php


namespace Lifo\CiscoISE;

class EndPointGroup extends AbstractObject
{
    const string CONFIG_URI      = 'config/endpointgroup';
    const string CONFIG_NAME_URI = self::CONFIG_URI . '/name';
    const string JSON_ROOT_KEY   = 'EndPointGroup';

    protected ?string $id            = null;
    protected ?string $name          = null;
    protected ?string $description   = null;
    protected bool    $systemDefined = false;

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

    public function isSystemDefined(): bool
    {
        return $this->systemDefined;
    }

    public function setSystemDefined(bool $systemDefined): self
    {
        $this->systemDefined = $systemDefined;
        return $this;
    }


}