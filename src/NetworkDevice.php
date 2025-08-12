<?php


namespace Lifo\CiscoISE;

class NetworkDevice extends AbstractObject
{
    const string CONFIG_URI    = 'config/networkdevice';
    const string JSON_ROOT_KEY = 'NetworkDevice';

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
        return match ($prop) {
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

    public function getDtlsDnsName(): ?string
    {
        return $this->dtlsDnsName;
    }

    public function setDtlsDnsName(?string $dtlsDnsName): self
    {
        $this->dtlsDnsName = $dtlsDnsName;
        return $this;
    }

    public function getProfileName(): ?string
    {
        return $this->profileName;
    }

    public function setProfileName(?string $profileName): self
    {
        $this->profileName = $profileName;
        return $this;
    }

    public function getCoaPort(): ?int
    {
        return $this->coaPort;
    }

    public function setCoaPort(?int $coaPort): self
    {
        $this->coaPort = $coaPort;
        return $this;
    }

    public function setAuthenticationSettings($settings): self
    {
        if ($settings && !$settings instanceof AuthenticationSettings) {
            $settings = new AuthenticationSettings($settings);
        }
        $this->authenticationSettings = $settings;
        return $this;
    }

    public function getAuthenticationSettings(): AuthenticationSettings
    {
        if (!$this->authenticationSettings) {
            $this->authenticationSettings = new AuthenticationSettings();
        }
        return $this->authenticationSettings;
    }

    public function setTacacsSettings($settings): self
    {
        if ($settings && !$settings instanceof TacacsSettings) {
            $settings = new TacacsSettings($settings);
        }
        $this->tacacsSettings = $settings;
        return $this;
    }

    public function getTacacsSettings(): TacacsSettings
    {
        if (!$this->tacacsSettings) {
            $this->tacacsSettings = new TacacsSettings();
        }
        return $this->tacacsSettings;
    }

    public function setNetworkDeviceIPList($ips): self
    {
        if ($ips !== null && !$ips instanceof NetworkDeviceIPList) {
            $ips = new NetworkDeviceIPList($ips);
        }
        $this->networkDeviceIPList = $ips;
        return $this;
    }

    public function getNetworkDeviceIPList(): NetworkDeviceIPList
    {
        if (!$this->networkDeviceIPList) {
            $this->networkDeviceIPList = new NetworkDeviceIPList();
        }
        return $this->networkDeviceIPList;
    }

    public function setNetworkDeviceGroupList($groups): self
    {
        if ($groups !== null && !$groups instanceof NetworkDeviceGroupList) {
            $groups = new NetworkDeviceGroupList($groups);
        }
        $this->networkDeviceGroupList = $groups;
        return $this;
    }

    public function getNetworkDeviceGroupList(): NetworkDeviceGroupList
    {
        if (!$this->networkDeviceGroupList) {
            $this->networkDeviceGroupList = new NetworkDeviceGroupList();
        }
        return $this->networkDeviceGroupList;
    }

    public function setSnmpSettings($settings): self
    {
        if ($settings !== null && !$settings instanceof SnmpSettings) {
            $settings = new SnmpSettings($settings);
        }
        $this->snmpSettings = $settings;
        return $this;
    }

    public function getSnmpSettings(): SnmpSettings
    {
        if (!$this->snmpSettings) {
            $this->snmpSettings = new SnmpSettings();
        }
        return $this->snmpSettings;
    }


}