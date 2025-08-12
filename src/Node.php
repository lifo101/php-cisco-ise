<?php


namespace Lifo\CiscoISE;

class Node extends AbstractObject
{
    const string CONFIG_URI    = 'config/node';
    const string JSON_ROOT_KEY = 'Node';

    protected ?string $id               = null;
    protected ?string $name             = null;
    protected ?string $gateway          = null;
    protected ?string $username         = null;
    protected ?string $password         = null;
    protected ?string $displayName      = null;
    protected ?bool   $inDeployment     = null;
    protected ?string $otherPapFqdn     = null;
    protected ?string $ipAddress        = null;
    protected array   $ipAddresses      = [];
    protected ?string $nodeServiceTypes = null;
    protected ?bool   $primaryPapNode   = null;
    protected ?bool   $papNode          = null;
    protected ?bool   $pxGridNode       = null;

    public function mapPropToKey(string $prop): string
    {
        return match ($prop) {
            'gateway' => 'gateWay',
            'username' => 'userName',
            'password' => 'passWord',
            'trustSecSettings', 'snmpSettings' => strtolower($prop),
            'networkDeviceIPList', 'networkDeviceGroupList' => ucfirst($prop),
            default => $prop,
        };
    }

    public function __clone()
    {
        $this->id = null;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
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

    public function getGateway(): ?string
    {
        return $this->gateway;
    }

    public function setGateway(?string $gateway): self
    {
        $this->gateway = $gateway;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function getInDeployment(): ?bool
    {
        return $this->inDeployment;
    }

    public function setInDeployment(?bool $inDeployment): self
    {
        $this->inDeployment = $inDeployment;
        return $this;
    }

    public function getOtherPapFqdn(): ?string
    {
        return $this->otherPapFqdn;
    }

    public function setOtherPapFqdn(?string $otherPapFqdn): self
    {
        $this->otherPapFqdn = $otherPapFqdn;
        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    public function getIpAddresses(): array
    {
        return $this->ipAddresses;
    }

    public function setIpAddresses(array $ipAddresses): self
    {
        $this->ipAddresses = $ipAddresses;
        return $this;
    }

    public function getNodeServiceTypes(): ?string
    {
        return $this->nodeServiceTypes;
    }

    public function setNodeServiceTypes(?string $nodeServiceTypes): self
    {
        $this->nodeServiceTypes = $nodeServiceTypes;
        return $this;
    }

    public function getPrimaryPapNode(): ?bool
    {
        return $this->primaryPapNode;
    }

    public function setPrimaryPapNode(?bool $primaryPapNode): self
    {
        $this->primaryPapNode = $primaryPapNode;
        return $this;
    }

    public function getPapNode(): ?bool
    {
        return $this->papNode;
    }

    public function setPapNode(?bool $papNode): self
    {
        $this->papNode = $papNode;
        return $this;
    }

    public function getPxGridNode(): ?bool
    {
        return $this->pxGridNode;
    }

    public function setPxGridNode(?bool $pxGridNode): self
    {
        $this->pxGridNode = $pxGridNode;
        return $this;
    }


}