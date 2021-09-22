<?php


namespace Lifo\CiscoISE;

class VersionInfo extends AbstractObject
{
    const CONFIG_URI    = 'config/node/versioninfo';
    const JSON_ROOT_KEY = 'VersionInfo';

    protected ?string $currentServerVersion = null;
    protected array   $supportedVersions    = [];

    /**
     * @return string|null
     */
    public function getCurrentServerVersion(): ?string
    {
        return $this->currentServerVersion;
    }

    /**
     * @param string|null $currentServerVersion
     *
     * @return self
     */
    public function setCurrentServerVersion(?string $currentServerVersion): self
    {
        $this->currentServerVersion = $currentServerVersion;
        return $this;
    }

    /**
     * @return array
     */
    public function getSupportedVersions(): array
    {
        return $this->supportedVersions;
    }

    /**
     * @param string|string[] $versions
     *
     * @return self
     */
    public function setSupportedVersions($versions): self
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