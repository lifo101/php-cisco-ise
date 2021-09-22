<?php


namespace Lifo\CiscoISE;

class EndPoint extends AbstractObject
{
    const CONFIG_URI      = 'config/endpoint';
    const CONFIG_NAME_URI = self::CONFIG_URI . '/name';
    const JSON_ROOT_KEY   = 'ERSEndPoint';

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
    public function getMac(): ?string
    {
        return $this->mac;
    }

    /**
     * @param string|null $mac
     *
     * @return self
     */
    public function setMac(?string $mac): self
    {
        $this->mac = $mac;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProfileId(): ?string
    {
        return $this->profileId;
    }

    /**
     * @param string|null $profileId
     *
     * @return self
     */
    public function setProfileId(?string $profileId): self
    {
        $this->profileId = $profileId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGroupId(): ?string
    {
        return $this->groupId;
    }

    /**
     * @param string|EndPointGroup|null $groupId
     *
     * @return self
     */
    public function setGroupId($groupId): self
    {
        if ($groupId instanceof EndPointGroup) $groupId = $groupId->getId();
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPortalUser(): ?string
    {
        return $this->portalUser;
    }

    /**
     * @param string|null $portalUser
     *
     * @return self
     */
    public function setPortalUser(?string $portalUser): self
    {
        $this->portalUser = $portalUser;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdentityStore(): ?string
    {
        return $this->identityStore;
    }

    /**
     * @param string|null $identityStore
     *
     * @return self
     */
    public function setIdentityStore(?string $identityStore): self
    {
        $this->identityStore = $identityStore;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdentityStoreId(): ?string
    {
        return $this->identityStoreId;
    }

    /**
     * @param string|null $id
     *
     * @return self
     */
    public function setIdentityStoreId(?string $id): self
    {
        $this->identityStoreId = $id;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStaticProfileAssignment(): bool
    {
        return $this->staticProfileAssignment;
    }

    /**
     * @param bool $flag
     *
     * @return self
     */
    public function setStaticProfileAssignment(bool $flag): self
    {
        $this->staticProfileAssignment = $flag;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStaticGroupAssignment(): bool
    {
        return $this->staticGroupAssignment;
    }

    /**
     * @param bool $flag
     *
     * @return self
     */
    public function setStaticGroupAssignment(bool $flag): self
    {
        $this->staticGroupAssignment = $flag;
        return $this;
    }

}