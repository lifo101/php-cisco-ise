<?php


namespace Lifo\CiscoISE;

class Node extends AbstractObject
{
    const CONFIG_URI    = 'config/node';
    const JSON_ROOT_KEY = 'Node';

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
        switch ($prop) {
            case 'gateway':
                return 'gateWay';
            case 'username':
                return 'userName';
            case 'password':
                return 'passWord';
            case 'trustSecSettings':
            case 'snmpSettings':
                return strtolower($prop);
            case 'networkDeviceIPList':
            case 'networkDeviceGroupList':
                return ucfirst($prop);
        }
        return $prop;
    }

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
    public function setId(string $id): self
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
    public function getGateway(): ?string
    {
        return $this->gateway;
    }

    /**
     * @param string|null $gateway
     *
     * @return self
     */
    public function setGateway(?string $gateway): self
    {
        $this->gateway = $gateway;
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
     * @return self
     */
    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     *
     * @return self
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    /**
     * @param string|null $displayName
     *
     * @return self
     */
    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getInDeployment(): ?bool
    {
        return $this->inDeployment;
    }

    /**
     * @param bool|null $inDeployment
     *
     * @return self
     */
    public function setInDeployment(?bool $inDeployment): self
    {
        $this->inDeployment = $inDeployment;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOtherPapFqdn(): ?string
    {
        return $this->otherPapFqdn;
    }

    /**
     * @param string|null $otherPapFqdn
     *
     * @return self
     */
    public function setOtherPapFqdn(?string $otherPapFqdn): self
    {
        $this->otherPapFqdn = $otherPapFqdn;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    /**
     * @param string|null $ipAddress
     *
     * @return self
     */
    public function setIpAddress(?string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * @return array
     */
    public function getIpAddresses(): array
    {
        return $this->ipAddresses;
    }

    /**
     * @param array $ipAddresses
     *
     * @return self
     */
    public function setIpAddresses(array $ipAddresses): self
    {
        $this->ipAddresses = $ipAddresses;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNodeServiceTypes(): ?string
    {
        return $this->nodeServiceTypes;
    }

    /**
     * @param string|null $nodeServiceTypes
     *
     * @return self
     */
    public function setNodeServiceTypes(?string $nodeServiceTypes): self
    {
        $this->nodeServiceTypes = $nodeServiceTypes;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getPrimaryPapNode(): ?bool
    {
        return $this->primaryPapNode;
    }

    /**
     * @param bool|null $primaryPapNode
     *
     * @return self
     */
    public function setPrimaryPapNode(?bool $primaryPapNode): self
    {
        $this->primaryPapNode = $primaryPapNode;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getPapNode(): ?bool
    {
        return $this->papNode;
    }

    /**
     * @param bool|null $papNode
     *
     * @return self
     */
    public function setPapNode(?bool $papNode): self
    {
        $this->papNode = $papNode;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getPxGridNode(): ?bool
    {
        return $this->pxGridNode;
    }

    /**
     * @param bool|null $pxGridNode
     *
     * @return self
     */
    public function setPxGridNode(?bool $pxGridNode): self
    {
        $this->pxGridNode = $pxGridNode;
        return $this;
    }


}