<?php


namespace Lifo\CiscoISE;

class NetworkDevice extends AbstractObject
{
    const CONFIG_URI    = 'config/networkdevice';
    const JSON_ROOT_KEY = 'NetworkDevice';

    protected ?string                 $id                     = null;
    protected ?string                 $name                   = null;
    protected ?string                 $description            = null;
    protected ?string                 $dtlsDnsName            = null;
    protected ?string                 $profileName            = null;
    protected ?int                    $coaPort                = 1700;
    protected ?AuthenticationSettings $authenticationSettings = null;
    protected ?TacacsSettings         $tacacsSettings         = null;
    protected ?NetworkDeviceIPList    $networkDeviceIPList    = null;
    protected ?NetworkDeviceGroupList $networkDeviceGroupList = null;
    protected ?SnmpSettings           $snmpSettings           = null;

    public function mapPropToKey(string $prop): string
    {
        switch ($prop) {
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
     * @return NetworkDevice
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
     * @return NetworkDevice
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
     * @return NetworkDevice
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDtlsDnsName(): ?string
    {
        return $this->dtlsDnsName;
    }

    /**
     * @param string|null $dtlsDnsName
     *
     * @return NetworkDevice
     */
    public function setDtlsDnsName(?string $dtlsDnsName): self
    {
        $this->dtlsDnsName = $dtlsDnsName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProfileName(): ?string
    {
        return $this->profileName;
    }

    /**
     * @param string|null $profileName
     *
     * @return NetworkDevice
     */
    public function setProfileName(?string $profileName): self
    {
        $this->profileName = $profileName;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCoaPort(): ?int
    {
        return $this->coaPort;
    }

    /**
     * @param int|null $coaPort
     *
     * @return NetworkDevice
     */
    public function setCoaPort(?int $coaPort): self
    {
        $this->coaPort = $coaPort;
        return $this;
    }

    /**
     * @param AuthenticationSettings|object|null $settings
     *
     * @return NetworkDevice
     */
    public function setAuthenticationSettings($settings): self
    {
        if ($settings && !$settings instanceof AuthenticationSettings) {
            $settings = new AuthenticationSettings($settings);
        }
        $this->authenticationSettings = $settings;
        return $this;
    }

    /**
     * @return AuthenticationSettings
     */
    public function getAuthenticationSettings(): AuthenticationSettings
    {
        if (!$this->authenticationSettings) {
            $this->authenticationSettings = new AuthenticationSettings();
        }
        return $this->authenticationSettings;
    }

    /**
     * @param TacacsSettings|object|null $settings
     *
     * @return NetworkDevice
     */
    public function setTacacsSettings($settings): self
    {
        if ($settings && !$settings instanceof TacacsSettings) {
            $settings = new TacacsSettings($settings);
        }
        $this->tacacsSettings = $settings;
        return $this;
    }

    /**
     * @return TacacsSettings
     */
    public function getTacacsSettings(): TacacsSettings
    {
        if (!$this->tacacsSettings) {
            $this->tacacsSettings = new TacacsSettings();
        }
        return $this->tacacsSettings;
    }

    /**
     * @param NetworkDeviceIPList|array|null $ips
     *
     * @return NetworkDevice
     */
    public function setNetworkDeviceIPList($ips): self
    {
        if ($ips !== null && !$ips instanceof NetworkDeviceIPList) {
            $ips = new NetworkDeviceIPList($ips);
        }
        $this->networkDeviceIPList = $ips;
        return $this;
    }

    /**
     * @return NetworkDeviceIPList
     */
    public function getNetworkDeviceIPList(): NetworkDeviceIPList
    {
        if (!$this->networkDeviceIPList) {
            $this->networkDeviceIPList = new NetworkDeviceIPList();
        }
        return $this->networkDeviceIPList;
    }

    /**
     * @param NetworkDeviceGroupList|array|null $groups
     *
     * @return NetworkDevice
     */
    public function setNetworkDeviceGroupList($groups): self
    {
        if ($groups !== null && !$groups instanceof NetworkDeviceGroupList) {
            $groups = new NetworkDeviceGroupList($groups);
        }
        $this->networkDeviceGroupList = $groups;
        return $this;
    }

    /**
     * @return NetworkDeviceGroupList
     */
    public function getNetworkDeviceGroupList(): NetworkDeviceGroupList
    {
        if (!$this->networkDeviceGroupList) {
            $this->networkDeviceGroupList = new NetworkDeviceGroupList();
        }
        return $this->networkDeviceGroupList;
    }

    /**
     * @param SnmpSettings|array|null $settings
     *
     * @return NetworkDevice
     */
    public function setSnmpSettings($settings): self
    {
        if ($settings !== null && !$settings instanceof SnmpSettings) {
            $settings = new SnmpSettings($settings);
        }
        $this->snmpSettings = $settings;
        return $this;
    }

    /**
     * @return SnmpSettings
     */
    public function getSnmpSettings(): SnmpSettings
    {
        if (!$this->snmpSettings) {
            $this->snmpSettings = new SnmpSettings();
        }
        return $this->snmpSettings;
    }


}