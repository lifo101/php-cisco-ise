<?php


namespace Lifo\CiscoISE;

use CurlHandle;
use Exception;
use InvalidArgumentException;
use ReflectionClass;
use SimpleXMLElement;

class CiscoISEClient
{
    use ExceptionsTrait;

    /** @var array List of hosts to attempt API calls against */
    private array $hosts;
    /** @var string Current host to use for API calls */
    private string  $host;
    private int     $port;
    private string  $username;
    private string  $password;
    private int     $timeout;
    private bool    $debug;
    private ?string $lastUrl;
    private ?int    $errno;
    private ?string $error;
    private ?int    $headerIndex;
    /** @var string[] List of headers received */
    private array $headers;
    /** @var int|null Last HTTP code received */
    private ?int $httpStatus;
    /** @var string[] List of headers to send */
    private array $sendHeaders;
    /** @var string API Version */
    private string $version;
    /** @var string[] List of default parameters to send on all requests */
    private array $defaults;
    /** @var string|null last POST data */
    private ?string $post;
    /** @var object|null last hydrated response received */
    private ?object $response;
    /** @var object|null last JSON object received */
    private ?object $json;

    /**
     * CiscoISEClient constructor.
     *
     * @param string|string[] $host
     * @param string          $username
     * @param string          $password
     * @param string          $version
     */
    public function __construct(array|string $host, string $username, string $password, string $version = '2.4')
    {
        $this->setHosts($host);
        $this->setPort(9060);
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setDebug(false);
        $this->setTimeout(5);
        $this->lastUrl = null;
        $this->json = null;
        $this->version = $version;
        $this->errno = null;
        $this->error = null;
        $this->headers = [];
        $this->httpStatus = null;
        $this->headerIndex = null;
    }

    protected function curlInit(?string $url, $method = null, string $contentType = 'json'): CurlHandle|false
    {
        $ch = curl_init();
        $this->lastUrl = $url;

        curl_setopt($ch, CURLOPT_URL, $url);
        // must send user/pass with each request (ISE doesn't seem to like using the cookie that it sends back)
        curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // ISE API uses 'Location:' for other purposes
//        curl_setopt($ch, CURLOPT_SSLVERSION, 1); // don't set SSL version; use default
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, [$this, 'processHeader']);
        curl_setopt($ch, CURLOPT_VERBOSE, $this->isDebug());

        if (!empty($method)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }

        $this->headerIndex = null;
        $this->sendHeaders = [];
        $this->sendHeaders[] = 'Accept: application/' . $contentType;

        return $ch;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     *
     * @return self
     */
    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;
        return $this;
    }

    protected function buildUrl($op, ?array $params = null): string
    {
        // Is the $op a fully qualified URL?
        if (str_starts_with($op, 'http')) {
            $url = $op;
        } else {
            $url = 'https://' . $this->host . ':' . $this->port . '/ers/' . ltrim($op ?: '', '/');
        }

        if ($params === null) {
            $params = [];
        }

        // add defaults
        if (!empty($this->defaults)) {
            $params = array_replace($params, $this->defaults);
        }

        if (!empty($params)) {
            // If the URL has a query string directly in it then make sure $params does not have any conflicting vars
            $q = parse_url($url, PHP_URL_QUERY);
            if ($q) {
                $overrides = [];
                parse_str($q, $overrides);
                $params = array_diff_key($params, $overrides);
            }

            if (!empty($params)) {
                $q = http_build_query($params);
                // replace 'filter[x]=' with 'filter='
                $q = preg_replace('/%5B\d+%5D/imU', '', $q);
                $url .= '?' . $q;
            }
        }

        return $url;
    }

    /**
     * @param string|CurlHandle $ch
     * @param array|string|null $post
     * @param string            $contentType
     *
     * @return object|null
     * @throws Exception on failure
     */
    protected function curl(string|CurlHandle $ch, array|string|null $post = null, string $contentType = 'json'): ?object
    {
        if (!$ch instanceof CurlHandle) {
            $ch = $this->curlInit($ch);
        }

        $this->post = null;
        if (!empty($post)) {
            if (!is_string($post)) {
                $post = json_encode($post);
            }
            $this->post = $post;
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $this->sendHeaders[] = 'Content-Type: application/' . $contentType;
        }

        if ($this->sendHeaders) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->sendHeaders);
        }

        $this->error = null;
        $str = curl_exec($ch);
        $this->httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->processError($ch);
        $this->response = $this->processResponse($str);
        curl_close($ch);
        return $this->response;
    }

    /**
     * @param CurlHandle $ch Curl Handle
     *
     * @throws Exception on failure
     */
    protected function processError(CurlHandle $ch): void
    {
        $this->errno = curl_errno($ch);
        $this->error = curl_error($ch) ?: null;
        if (str_starts_with($this->httpStatus, '2')) return;
        $this->errno = $this->httpStatus;
        switch (true) {
            case $this->httpStatus >= 500:
                $this->error = 'Internal Server Error';
                break;
            case $this->httpStatus === 401:
                $this->error = 'Invalid Credentials';
                break;
            case $this->httpStatus === 403:
                $this->error = 'Unauthorized';
                break;
            case $this->httpStatus === 404:
                $this->error = 'Not Found';
                break;
            case $this->httpStatus === 415:
                $this->error = 'Unsupported Media Type';
                break;
            case $this->httpStatus >= 400:
                $this->error = 'Bad Request';
                break;
        }

        if ($this->error) {
            throw new ISEError(null, $this->error, $this->httpStatus);
        }
    }

    protected function processResponse(mixed $str): ?object
    {
        if (!$str) return null;
        $json = json_decode($str);
        $this->json = $json;
        // API always returns an JSON object with a single property.
        if ($json) {
            $props = array_keys(get_object_vars($json));
            $type = $props[0];
            $cls = $this->mapTypeToClass($type);
            try {
                return $this->hydrateLocalObject($json->$type, $cls);
            } catch (Exception) {
                return $json;
            }
        }
        return $json;
    }

    /**
     * Convert the object given into the corresponding Class, if possible
     *
     * @param string $type
     * @param object $obj
     *
     * @return object
     */
    protected function hydrateLocalObject(object $obj, string $type): object
    {
        $class = __NAMESPACE__ . '\\' . $type;
        if (class_exists($class)) {
            return match (true) {
                $class === SearchResult::class => new $class($obj, $this),
                default => new $class($obj),
            };
        } else {
            return $obj;
        }
    }

    /**
     * Return the last response.
     */
    public function getResponse(): ?object
    {
        return $this->response;
    }

    public function getJson(): ?object
    {
        return $this->json;
    }

    /**
     * Return the last POST data
     */
    public function getPost(): ?string
    {
        return $this->post;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return self
     */
    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }

    public function setHosts(array|string $hosts): self
    {
        $this->hosts = is_iterable($hosts) ? (array)$hosts : [$hosts];
        $this->setHost(reset($this->hosts));
        return $this;
    }

    public function getHosts(): array
    {
        return $this->hosts;
    }

    /**
     * Determine the primary node in the cluster and set it as the current host for API calls.
     * {@link $hosts} must be set.
     *
     * @return Node|null
     * @throws Exception
     * @throws Exception
     */
    public function determinePrimaryNode(): ?Node
    {
        $origHost = $this->getHost();
        $hosts = $this->getHosts();
        foreach ($hosts as $host) {
            $this->setHost($host);
            $s = $this->getNodes();
            if (!$s || !$s->getTotal()) continue;
            $s->setHydrate(true);
            /** @var Node $node */
            foreach ($s as $node) {
                if ($node->getPrimaryPapNode()) {
                    $this->setHost($node->getIpAddress());
                    return $node;
                }
            }
        }
        $this->setHost($origHost);
        return null;
    }

    /**
     * Get current and supported versions.
     *
     * @return VersionInfo|null
     * @throws Exception
     * @throws Exception
     */
    public function getVersions(): ?VersionInfo
    {
        $url = $this->buildUrl('config/node/versioninfo');
        return $this->curl($url);
    }

    public function setPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * Return the last URL requested
     */
    public function getLastUrl(): ?string
    {
        return $this->lastUrl;
    }

    /**
     * Return the matching header from the last response received. Note, if you make several requests you'll only see
     * the last response.
     *
     * @param string $name
     *
     * @return string|string[]|null
     */
    public function getHeader(string $name): array|string|null
    {
        if (!$this->headers) {
            return null;
        }
        $name = strtolower($name);
        $headers = end($this->headers);
        $headers = array_combine(array_map(strtolower(...), array_keys($headers)), array_values($headers));
        if (array_key_exists($name, $headers)) {
            return $headers[$name];
        }
        return null;
    }

    /**
     * Return all headers
     *
     * @return string[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Low level command to GET anything. Mainly used from the CiscoISEIterator but can be used for other ad-hoc
     * queries as well.
     *
     * @param string $op
     * @param array  $params
     *
     * @return object|null
     * @throws Exception
     */
    public function get(string $op, array $params = []): ?object
    {
        return $this->curl($this->buildUrl($op, $params));
    }

    /**
     * Fetch a list of nodes.
     *
     * @param array $params
     *
     * @return object|null
     * @throws Exception
     */
    public function getNodes(array $params = []): ?object
    {
        $url = $this->buildUrl('config/node', $params);
        return $this->curl($url);
    }

    /**
     * Fetch a list of endpoint identity groups.
     *
     * @param array|null $params
     *
     * @return object|null
     * @throws Exception
     */
    public function getEndpointGroups(?array $params = null): ?object
    {
        $url = $this->buildUrl('config/endpointgroup', $params);
        return $this->curl($url);
    }

    /**
     * Fetch an endpoint identity group by ID or name.
     *
     * @param string $id
     * @param bool   $byName
     *
     * @return EndPointGroup|null
     * @throws Exception
     */
    public function getEndpointGroup(string $id, ?bool $byName = null): ?object
    {
        $url = $this->buildUrl(($byName ? EndPointGroup::CONFIG_NAME_URI : EndPointGroup::CONFIG_URI) . '/' . rawurlencode($id));
        return $this->curl($url);
    }

    /**
     * Fetch an endpoint identity group by name.
     *
     * @param string $name
     *
     * @return EndPointGroup|null
     * @throws Exception
     */
    public function getEndpointGroupByName(string $name): ?object
    {
        $url = $this->buildUrl(EndPointGroup::CONFIG_NAME_URI . '/' . rawurlencode($name));
        return $this->curl($url);
    }

    /**
     * Fetch a list of endpoints. Not safe to paginate through this result set due to the large result set (>100k).
     *
     * @param array|null $params
     *
     * @return SearchResult|object
     * @throws Exception
     */
    public function getEndPoints(?array $params = null): ?object
    {
        $url = $this->buildUrl(EndPoint::CONFIG_URI, $params);
        return $this->curl($url);
    }

    /**
     * Fetch a list of ANC endpoints.
     *
     * @param array|null $params
     *
     * @return SearchResult|object
     * @throws Exception
     */
    public function getAncEndPoints(?array $params = null): ?object
    {
        $url = $this->buildUrl(AncEndPoint::CONFIG_URI, $params);
        return $this->curl($url);
    }

    /**
     * Fetch an ANC EndPoint.
     *
     * @param string|EndPoint $id
     * @param array|null      $params
     *
     * @return object|null
     * @throws Exception
     */
    public function getAncEndpoint(string|EndPoint $id, ?array $params = null): ?object
    {
        if ($id instanceof EndPoint) $id = $id->getId();
        $url = $this->buildUrl(sprintf(AncEndPoint::CONFIG_URI . '/%s', rawurlencode($id)), $params);
        return $this->curl($url);
    }


    /**
     * Fetch a list of network devices.
     *
     * @param array|null $params
     *
     * @return SearchResult|null
     * @throws Exception
     */
    public function getNetworkDeviceGroups(?array $params = null): ?object
    {
        $url = $this->buildUrl('config/networkdevicegroup', $params);
        return $this->curl($url);
    }

    /**
     * Fetch a single network device group by it's ID.
     *
     * @param string     $id
     * @param array|null $params
     *
     * @return NetworkDeviceGroup|null
     * @throws Exception
     */
    public function getNetworkDeviceGroup(string $id, ?array $params = null): ?NetworkDeviceGroup
    {
        $url = $this->buildUrl(sprintf('config/networkdevicegroup/%s', $id), $params);
        return $this->curl($url);
    }

    /**
     * Fetch a single network device group by it's ID.
     *
     * @param string     $name
     * @param array|null $params
     *
     * @return NetworkDeviceGroup|null
     * @throws Exception
     */
    public function getNetworkDeviceGroupByName(string $name, ?array $params = null): ?NetworkDeviceGroup
    {
        try {
            $url = $this->buildUrl(sprintf('config/networkdevicegroup/name/%s', rawurlencode(str_replace('#', ':', $name))), $params);
            return $this->curl($url);
        } catch (ISEError) {
            return null;
        }
    }

    /**
     * Fetch a single network device group by it's Building Name.
     * This is a shortcut for fetching NetworkDeviceGroup('Location#All Locations#{BUILDING}')
     *
     * @param string $name
     * @param null   $params
     *
     * @return NetworkDeviceGroup|null
     */
    public function getNetworkDeviceGroupByBuilding(string $name, $params = null): ?NetworkDeviceGroup
    {
        try {
            return $this->getNetworkDeviceGroupByName('Location#All Locations#' . $name, $params);
        } catch (Exception) {
            return null;
        }
    }

    /**
     * Fetch a list of network devices.
     *
     * @param array|null $params
     *
     * @return SearchResult|object
     * @throws Exception
     */
    public function getNetworkDevices(?array $params = null): ?object
    {
        $url = $this->buildUrl('config/networkdevice', $params);
        return $this->curl($url);
    }

    /**
     * Fetch a single network device by it's ID.
     *
     * @param string     $id
     * @param array|null $params
     *
     * @return object|null
     * @throws Exception
     */
    public function getNetworkDevice(string $id, ?array $params = null): ?object
    {
        $url = $this->buildUrl(sprintf('config/networkdevice/%s', $id), $params);
        return $this->curl($url);
    }

    /**
     * Find a Network Device based on its name or IP address. Must match exactly, or null is returned
     *
     * @param string $match
     *
     * @return NetworkDevice|null
     * @throws Exception
     */
    public function findNetworkDevice(string $match): ?NetworkDevice
    {
//        $m = rawurlencode($match);
        $res = $this->getNetworkDevices([
            'size'       => 2,
            'filtertype' => 'or',
            'filter'     => [
                'name.EQ.' . $match,
                'ipaddress.EQ.' . $match,
            ]
        ]);
        if ($res instanceof SearchResult) {
            if ($res->getTotal() === 1) {
                $res = $res->getResources()[0];
                return $this->get($res->link->href);
            }
        }
        return null;
    }

    /**
     * Fetch a list of ANC Policies.
     *
     * @param array|null $params
     *
     * @return SearchResult|object
     * @throws Exception
     */
    public function getAncPolicies(?array $params = null): ?object
    {
        $url = $this->buildUrl(AncPolicy::CONFIG_URI, $params);
        return $this->curl($url);
    }

    /**
     * Fetch an ANC Policy by ID or name.
     *
     * @param string $id
     * @param bool   $byName
     *
     * @return AncPolicy|null
     * @throws Exception
     */
    public function getAncPolicy(string $id, ?bool $byName = null): ?AncPolicy
    {
        $url = $this->buildUrl(($byName ? AncPolicy::CONFIG_NAME_URI : AncPolicy::CONFIG_URI) . '/' . rawurlencode($id));
        return $this->curl($url);
    }

    public function getAncPolicyByName(string $name): ?AncPolicy
    {
        return $this->getAncPolicy($name, true);
    }

    /**
     * Apply or clear ANC Policy on the mac(s) (or {@link EndPoint}s) provided.
     * If Policy is null its cleared from the EndPoint
     *
     * @param string|EndPoint|EndPoint[]|string[] $macs   MAC Address string, or EndPoint
     * @param string|AncPolicy|null               $policy Null to clear the policy from the EndPoint
     *
     * @return bool
     * @throws Exception
     */
    public function ancApply(array|string|EndPoint $macs, string|AncPolicy|null $policy): bool
    {
        $ary = [];
        if (!is_iterable($macs)) $macs = [$macs];
        if ($policy instanceof AncPolicy) $policy = $policy->getId();
        foreach ($macs as $mac) {
            $ary[] = ['name' => 'macAddress', 'value' => $mac instanceof EndPoint ? $mac->getMac() : $mac];
            if ($policy) {
                $ary[] = ['name' => 'policyName', 'value' => $policy];
            }
        }

        $url = $this->buildUrl($policy ? AncEndPoint::CONFIG_APPLY_URI : AncEndPoint::CONFIG_CLEAR_URI);
        $ch = $this->curlInit($url, 'PUT');
        $res = $this->curl($ch, ['OperationAdditionalData' => ['additionalData' => $ary]]);
        if ($this->httpStatus === 204) return true;
        $err = new ISEError($res, sprintf('Error %s policy%s on %s',
            $policy ? 'applying' : 'clearing',
            $policy ? ' ' . $policy : '',
            implode(',', $macs)));
        // account for a couple of simple use-cases
        if (!$policy && stripos($err->getMessage(), 'not associated') !== false) return false;
        if ($policy && stripos($err->getMessage(), 'already associated') !== false) return false;
        throw $err;
    }

    /**
     * Clear an ANC Policy for a Mac|EndPoint.
     *
     * @param $macs
     *
     * @return bool
     * @throws Exception
     */
    public function ancClear($macs): bool
    {
        return $this->ancApply($macs, null);
    }

    /**
     * Perform an Create request on the object given
     *
     * @param ObjectInterface|array $obj
     * @param string                $cls     Class name of object being updated
     * @param string                $uri     The URI of the operation to perform (eg: config/networkdevice)
     * @param string                $rootKey The root key for the JSON requestion (eg: 'NetworkDevice')
     * @param bool                  $hydrate Hydrate the response into a real class
     *
     * @return SearchResult|ObjectInterface|string|bool|null
     * @throws Exception
     */
    protected function doCreate(ObjectInterface|array $obj, string $cls, string $uri, string $rootKey, bool $hydrate = false): SearchResult|ObjectInterface|string|bool|null
    {
        $ary = $obj instanceof ObjectInterface ? $obj->toArray() : (array)$obj;
        $url = $this->buildUrl($uri);
        $ch = $this->curlInit($url, 'POST');
        $res = $this->curl($ch, [$rootKey => $ary]);
        if ($this->httpStatus === 201) {
            $url = $this->getHeader('Location');
            $parts = explode('/', $url);
            $id = end($parts);
            return $hydrate ? $this->get($url) : $id;
        }
        try {
            $name = new ReflectionClass($cls)->getShortName();
        } catch (Exception) {
            $name = $cls;
        }
        throw new ISEError($res, sprintf("Error creating %s", $name));
    }

    /**
     * Perform an Update request on the object given
     *
     * @param ObjectInterface|array $obj
     * @param string                $cls     Class name of object being updated
     * @param string                $uri     The URI of the operation to perform (eg: config/networkdevice)
     * @param string                $rootKey The root key for the JSON requestion (eg: 'NetworkDevice')
     *
     * @return bool
     * @throws ISEError on failure
     * @throws Exception
     */
    protected function doUpdate(ObjectInterface|array $obj, string $cls, string $uri, string $rootKey): bool
    {
        $ary = $obj instanceof ObjectInterface ? $obj->toArray() : (array)$obj;
        if (!$ary) {
//            throw self::createInvalidArgumentException(func_get_args(), sprintf('Invalid Invalid argument provided (must be %s or array)', $cls));
            return false;
        }
        $id = $ary['id'] ?? null;
        if (!$id) {
            throw self::createInvalidArgumentException(func_get_args(), 'ID required when updating a ' . $cls);
        }
        $url = $this->buildUrl($uri . '/' . rawurlencode($id));
        $ch = $this->curlInit($url, 'PUT');
        $res = $this->curl($ch, [$rootKey => $ary]);
        if ($this->httpStatus === 200) return true;

        try {
            $name = new ReflectionClass($cls)->getShortName();
        } catch (Exception) {
            $name = $cls;
        }
        throw new ISEError($res, sprintf("Error updating %s#%s", $name, $id));
    }

    /**
     * @param ObjectInterface|array|string|int $obj
     * @param string                           $uri
     *
     * @return bool
     */
    protected function doDelete(ObjectInterface|array|string|int $obj, string $uri): bool
    {
        $id = $this->extractId($obj);
        if (!$id) {
            throw self::createInvalidArgumentException(func_get_args(), 'Invalid argument provided: Could not determine ID from $obj');
        }
        $url = $this->buildUrl($uri . '/' . rawurlencode($id));
        $ch = $this->curlInit($url, 'DELETE');
        try {
            $this->curl($ch);
            return $this->httpStatus === 204;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * @param NetworkDevice|array $dev
     * @param bool                $hydrate If true, fetch the newly created object and return it instead of the ID string
     *
     * @return bool|NetworkDevice|string|null if $hydrate is true then NetworkDevice else ID string
     * @throws ISEError|Exception on failure
     */
    public function createNetworkDevice(NetworkDevice|array $dev, bool $hydrate = false): NetworkDevice|bool|string|null
    {
        return $this->doCreate($dev, NetworkDevice::class, NetworkDevice::CONFIG_URI, NetworkDevice::JSON_ROOT_KEY, $hydrate);
    }

    /**
     * @param ObjectInterface|array $dev
     *
     * @return bool
     * @throws Exception on failure
     */
    public function updateNetworkDevice(ObjectInterface|array $dev): bool
    {
        return $this->doUpdate($dev, NetworkDevice::class, NetworkDevice::CONFIG_URI, NetworkDevice::JSON_ROOT_KEY);
    }

    /**
     * @param NetworkDevice|string $dev
     *
     * @return bool True on success
     */
    public function deleteNetworkDevice(NetworkDevice|string $dev): bool
    {
        return $this->doDelete($dev, NetworkDevice::CONFIG_URI);
    }


    /**
     * @param NetworkDeviceGroup|array $dev
     * @param bool                     $hydrate If true, fetch the newly created object and return it instead of the ID string
     *
     * @return bool|SearchResult|string|null if $hydrate is true then NetworkDeviceGroup else ID string
     * @throws ISEError|Exception on failure
     */
    public function createNetworkDeviceGroup(NetworkDeviceGroup|array $dev, bool $hydrate = false): SearchResult|bool|string|null
    {
        return $this->doCreate($dev, NetworkDeviceGroup::class, NetworkDeviceGroup::CONFIG_URI, NetworkDeviceGroup::JSON_ROOT_KEY, $hydrate);
    }

    /**
     * @param NetworkDeviceGroup|string $dev
     *
     * @return bool True on success
     */
    public function deleteNetworkDeviceGroup(NetworkDeviceGroup|string $dev): bool
    {
        return $this->doDelete($dev, NetworkDeviceGroup::CONFIG_URI);
    }

    /**
     * @param EndPoint|array $ep
     * @param bool           $hydrate If true, fetch the newly created object and return it instead of the ID string
     *
     * @return bool|SearchResult|string|null if $hydrate is true then EndPoint else ID string
     * @throws ISEError|Exception on failure
     */
    public function createEndPoint(EndPoint|array $ep, bool $hydrate = false): EndPoint|bool|string|null
    {
        return $this->doCreate($ep, EndPoint::class, EndPoint::CONFIG_URI, EndPoint::JSON_ROOT_KEY, $hydrate);
    }

    /**
     * @param EndPoint|array $ep
     *
     * @return bool
     * @throws ISEError|Exception on failure
     */
    public function updateEndPoint(EndPoint|array $ep): bool
    {
        return $this->doUpdate($ep, EndPoint::class, EndPoint::CONFIG_URI, EndPoint::JSON_ROOT_KEY);
    }

    /**
     * @param EndPoint|string $ep
     *
     * @return bool True on success
     */
    public function deleteEndPoint(EndPoint|string $ep): bool
    {
        return $this->doDelete($ep, EndPoint::CONFIG_URI);
    }

    /**
     * Create 1 or more EndPoints using the Bulk API
     *
     * @param object[] $list EndPoints to create
     *
     * @return string|bool Bulk ID if successful
     */
    public function createEndPoints(array $list): bool|string
    {
        $this->doEndPointBulkRequest('create', $list);
        if ($this->httpStatus === 202) {
            $url = $this->getHeader('Location');
            $parts = explode('/', $url);
            return end($parts);
        }
        return false;
    }

    /**
     * Delete 1 or more EndPoints using the Bulk API
     *
     * @param object[]|string[] $list EndPoints to create
     *
     * @return string|bool Bulk ID if successful
     */
    public function deleteEndPoints(array $list): bool|string
    {
        $this->doEndPointBulkRequest('delete', $list);
        if ($this->httpStatus === 202) {
            $url = $this->getHeader('Location');
            $parts = explode('/', $url);
            return end($parts);
        }
        return false;
    }

    /**
     *
     * @param string $id ID or URL of bulk status
     *
     * @return object|false|null
     * @throws Exception
     */
    public function getBulkStatus(string $id): object|null|false
    {
        if (str_contains($id, '/')) {
            $parts = explode('/', $id);
            $id = end($parts);
        }
        $url = $this->buildUrl('config/endpoint/bulk/' . $id);
        $res = $this->curl($url);
        // todo: $res could contain useful information if it fails
        return $this->httpStatus === 200 ? $res : false;
    }

    /**
     * Send a Bulk EndPoint request
     *
     * @param string   $op
     * @param object[] $endPoints
     *
     * @return object|null
     */
    public function doEndPointBulkRequest(string $op, array $endPoints): ?object
    {
        $url = $this->buildUrl('config/endpoint/bulk');
        $ch = $this->curlInit($url, 'PUT');

        // bulk requests only work with XML in API v2.4
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes" ?><ns4:endpointBulkRequest xmlns:ns6="sxp.ers.ise.cisco.com" xmlns:ns5="trustsec.ers.ise.cisco.com" xmlns:ns8="network.ers.ise.cisco.com" xmlns:ns7="anc.ers.ise.cisco.com" xmlns:ers="ers.ise.cisco.com" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:ns4="identity.ers.ise.cisco.com"></ns4:endpointBulkRequest>');
        $xml->addAttribute('operationType', $op);
        $xml->addAttribute('resourceMediaType', 'vnd.com.cisco.ise.identity.endpoint.1.0+xml');
        $data = match ($op) {
            'create' => $this->buildCreateEndPointData($xml, $endPoints),
            'delete' => $this->buildDeleteEndPointData($xml, $endPoints),
            default => throw new InvalidArgumentException("Invalid operation specified: \"$op\""),
        };
        try {
            return $this->curl($ch, $data, 'xml');
        } catch (Exception) {
            return null;
        }
    }

    protected function buildCreateEndPointData(SimpleXMLElement $xml, $resources): string|array|null
    {
        $children = $xml->addChild('ns4:resourcesList');
        foreach ($resources as $r) {
            $r = $r instanceof ObjectInterface ? $r->toArray() : (array)$r;
            $desc = $r['description'];
            unset($r['description']);
            $c = $children->addChild('ns4:endpoint');
            $c->addAttribute('description', $desc);

            // Only these properties can be modified (and in this order) or the bulk API complains
            static $myKeys = ['link', 'customAttributes', 'groupId', 'identityStore', 'identityStoreId', 'mac', 'mdmAttributes', 'portalUser', 'profileId', 'staticGroupAssignment', 'staticProfileAssignment'];
            foreach ($myKeys as $k) {
                if (!array_key_exists($k, $r)) {
                    continue;
                }
                $v = $r[$k];
                if (is_bool($v)) {
                    $v = $v ? 'true' : 'false';
                }
                $c->addChild($k, $v, '');
            }
        }
        // cleanup some XML so the API won't complain
        $data = str_replace(' xmlns=""', '', $xml->asXML());
        return preg_replace('~<(\w[\w\d_]+)/>~', '<\\1></\\1>', $data);
    }

    protected function buildDeleteEndPointData(SimpleXMLElement $xml, $resources): string
    {
        $children = $xml->addChild('idList', null, '');
        foreach ($resources as $r) {
            $id = $r instanceof ObjectInterface ? $r->getId() : $r;
            if (!$id) {
                continue;
            }
            $children->addChild('id', $id, '');
        }
        // cleanup some XML so the API won't complain
        return str_replace(' xmlns=""', '', $xml->asXML());
    }

    /**
     * Find an End Point based on it's MAC address
     *
     * @param string $mac
     *
     * @return EndPoint|null
     * @throws Exception
     */
    public function findEndPoint(string $mac): ?EndPoint
    {
        // if we can find it via "name" then we can save a call and return faster
        $url = $this->buildUrl(EndPoint::CONFIG_NAME_URI . '/' . rawurlencode($mac));
        try {
            $res = $this->curl($url);
        } catch (Exception $e) {
            if ($this->httpStatus === 404) {
                return null;
            }
            throw $e;
        }
        // errno=28 === Timed Out
        if ($res && $this->httpStatus === 200 || $this->errno === 28) return $res;

        // convert mac from any format into "XX:XX:XX:XX:XX:XX"
        $mac = implode(':', str_split(preg_replace('/[^a-zA-Z0-9]/', '', $mac), 2));
        $res = $this->getEndPoints(['filter' => 'mac.EQ.' . rawurlencode($mac)]);
        if ($res instanceof SearchResult && $res->getTotal() === 1) {
            $res = $res->getResources()[0];
            return $this->get($res->link->href);
        }
        return null;
    }

    /**
     * Fetch a list of endpoint identity groups.
     *
     * @param array|null $params
     *
     * @return object|null
     * @throws Exception
     */
    public function getIdentityGroups(?array $params = null): ?object
    {
        $url = $this->buildUrl('config/identitygroup', $params);
        return $this->curl($url);
    }

    /**
     * Return the last HTTP code received
     *
     * @return int|null
     */
    public function getHttpStatus(): ?int
    {
        return $this->httpStatus;
    }

    /**
     * Attempt to hydrate the given object from the server.
     * Object must be one that was returned from the server.
     * Returns original object if it cannot be hydrated.
     *
     * @param object|null $obj
     *
     * @return object|null
     * @throws Exception
     * @throws Exception
     */
    public function hydrate(?object $obj): ?object
    {
        if (is_object($obj) && isset($obj->link->href)) {
            $res = $this->get($obj->link->href);
            return $res ?: $obj;
        }
        return $obj;
    }

    /**
     * Set a default parameter to be sent on all requests
     *
     * @param string|null $name  Name of default to set. If NULL all defaults are cleared
     * @param mixed|null  $value Value of default.
     *
     * @return $this
     */
    public function setDefault(?string $name, mixed $value = null): self
    {
        if ($name === null) {
            $this->defaults = [];
        } else {
            $this->defaults[$name] = $value;
        }
        return $this;
    }

    /**
     * Process each header line. Since CURL may make multiple requests, the resulting headers array may have multiple
     * responses in it.
     *
     * @param $ch
     * @param $header
     *
     * @return int
     * @internal     Callback function only.
     * @noinspection PhpUnusedParameterInspection
     */
    protected function processHeader($ch, $header): int
    {
        $len = strlen($header);

        // increase index each time we get a new HTTP response
        if (preg_match('|^HTTP/\d+.\d+\s+\d+|', $header)) {
            $this->headerIndex = $this->headerIndex === null ? 0 : $this->headerIndex + 1;
            return $len;
        }

        // ignore invalid headers
        $header = explode(':', $header, 2);
        if (count($header) < 2) {
            return $len;
        }

        $i = $this->headerIndex;
        if (!isset($this->headers[$i])) {
            $this->headers[$i] = [];
        }

        $key = trim($header[0]);
        if (isset($this->headers[$i][$key])) {
            $this->headers[$i][$key] = [$this->headers[$i][$key]];
            $this->headers[$i][$key][] = trim($header[1]);
        } else {
            $this->headers[$i][$key] = trim($header[1]);
        }

        // must return the total bytes processed (which is the length of the header) or CURL will error
        return $len;
    }

    /**
     * Attempt to find the "id" in the parameter. could be a class, object, array...
     *
     * @param mixed $subject
     *
     * @return string|null
     */
    protected function extractId(mixed $subject): ?string
    {
        return match (true) {
            $subject instanceof ObjectInterface => $subject->getId(),
            is_array($subject) => $subject['id'] ?? null,
            is_string($subject) || is_int($subject) => (string)$subject,
            default => null,
        };
    }

    /**
     * Map the response type received from the server into a real local Class, if possible
     *
     * @param string $type
     *
     * @return string
     */
    private function mapTypeToClass(string $type): string
    {
        return match ($type) {
            'ERSEndPoint' => 'EndPoint',
            'ErsAncPolicy' => 'AncPolicy',
            default => $type,
        };
    }
}
