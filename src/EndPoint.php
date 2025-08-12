<?php


namespace Lifo\CiscoISE;

class EndPoint extends AbstractObject
{
    const string CONFIG_URI      = 'config/endpoint';
    const string CONFIG_NAME_URI = self::CONFIG_URI . '/name';
    const string JSON_ROOT_KEY   = 'ERSEndPoint';

    protected ?string $id                      = null;
    protected ?string $name                    = null;
    protected ?string $description             = null;
    protected ?string $mac                     = null;
    protected ?string $profileId               = null;
    protected ?string $groupId                 = null;
    protected ?string $portalUser              = null;
    protected ?string $identityStore           = null;
    protected ?string $identityStoreId         = null;
    protected bool    $staticProfileAssignment = false;
    protected bool    $staticGroupAssignment   = false;

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

    public function getMac(): ?string
    {
        return $this->mac;
    }

    public function setMac(?string $mac): self
    {
        $this->mac = $mac;
        return $this;
    }

    public function getProfileId(): ?string
    {
        return $this->profileId;
    }

    public function setProfileId(?string $profileId): self
    {
        $this->profileId = $profileId;
        return $this;
    }

    public function getGroupId(): ?string
    {
        return $this->groupId;
    }

    public function setGroupId(string|EndPointGroup|null $groupId): self
    {
        $this->groupId = $groupId instanceof EndPointGroup ? $groupId->getId() : $groupId;
        return $this;
    }

    public function getPortalUser(): ?string
    {
        return $this->portalUser;
    }

    public function setPortalUser(?string $portalUser): self
    {
        $this->portalUser = $portalUser;
        return $this;
    }

    public function getIdentityStore(): ?string
    {
        return $this->identityStore;
    }

    public function setIdentityStore(?string $identityStore): self
    {
        $this->identityStore = $identityStore;
        return $this;
    }

    public function getIdentityStoreId(): ?string
    {
        return $this->identityStoreId;
    }

    public function setIdentityStoreId(?string $id): self
    {
        $this->identityStoreId = $id;
        return $this;
    }

    public function isStaticProfileAssignment(): bool
    {
        return $this->staticProfileAssignment;
    }

    public function setStaticProfileAssignment(bool $flag): self
    {
        $this->staticProfileAssignment = $flag;
        return $this;
    }

    public function isStaticGroupAssignment(): bool
    {
        return $this->staticGroupAssignment;
    }

    public function setStaticGroupAssignment(bool $flag): self
    {
        $this->staticGroupAssignment = $flag;
        return $this;
    }

}