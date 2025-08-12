<?php


namespace Lifo\CiscoISE;

class VersionInfo extends AbstractObject
{
    const string CONFIG_URI    = 'config/node/versioninfo';
    const string JSON_ROOT_KEY = 'VersionInfo';

    protected ?string $currentServerVersion = null;
    protected array   $supportedVersions    = [];

    public function getCurrentServerVersion(): ?string
    {
        return $this->currentServerVersion;
    }

    public function setCurrentServerVersion(?string $currentServerVersion): self
    {
        $this->currentServerVersion = $currentServerVersion;
        return $this;
    }

    public function getSupportedVersions(): array
    {
        return $this->supportedVersions;
    }

    public function setSupportedVersions(array|string $versions): self
    {
        $this->supportedVersions = array_map(fn($v) => trim($v), (is_string($versions) ? explode(',', $versions) : $versions) ?? []);
        return $this;
    }

    /**
     * Returns true if the version provided is supported (<= currentServerVersion)
     *
     * @param string|null $ver
     *
     * @return bool
     */
    public function supports(?string $ver): bool
    {
        if (!$ver || !$this->currentServerVersion) return false;
        return version_compare($this->currentServerVersion, $ver, '>=');
    }
}