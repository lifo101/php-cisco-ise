<?php


namespace Lifo\CiscoISE;


class NetworkDeviceIPList extends AbstractListObject
{
    /**
     * Add an IP to the list
     *
     * @param object|string $ip
     *
     * @return self
     */
    public function add($ip): self
    {
        $ip = $this->extractIP($ip);
        return parent::add($ip);
    }

    /**
     * Remove an IP from the list
     *
     * @param object|string $ip
     *
     * @return self
     */
    public function remove($ip): self
    {
        $ip = $this->extractIP($ip);
        $this->list = array_values(array_filter($this->list,
            fn($item) => $item->ipaddress !== $ip->ipaddress && $item->mask !== $ip->mask
        ));

        return $this;
    }

    /**
     * Returns true if the IP exists
     *
     * @param $ip
     *
     * @return bool
     */
    public function exists($ip): bool
    {
        $ip = $this->extractIP($ip);
        return array_any($this->list, fn($item) => $item->ipaddress === $ip->ipaddress && $item->mask === $ip->mask);
    }

    public function getFirstIP(): ?string
    {
        $ip = $this->first();
        if (!$ip) return null;
        if ($ip->mask === 32 || $ip->mask === 128) {
            return $ip->ipaddress;
        }
        return $ip->ipaddress . '/' . $ip->mask;
    }

    private function extractIP($ip): ?object
    {
        if (!$ip) return null;
        if (is_array($ip)) $ip = (object)$ip;
        if (!is_object($ip)) {
            list($prefix, $mask) = array_pad(explode('/', $ip), 2, 32);
            $ip = (object)[
                'ipaddress' => $prefix,
                'mask'      => $mask,
            ];
        }
        return $ip;
    }
}