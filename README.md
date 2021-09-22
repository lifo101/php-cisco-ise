# Cisco Identity Engine REST Client

Interface with the Cisco Identity Engine REST API.

## Example

```php
use Lifo\CiscoISE\CiscoISEClient;

$ise = new CiscoISEClient('hostname', 'user', 'pass');
// or multiple hosts for failover (will detect the primary node)
// $ise = new CiscoISEClient(['hostname1', 'hostname2'], 'user', 'pass');

$ep = $ise->findEndPoint('0000.0000.0002');

$policy = $ise->getAncPolicy('Blacklist');

$ep = $this->ise->createEndPoint([
    'mac'         => '0000.0000.0002',
    'description' => 'Jason Test ' . time(),
], true);

$ep->setDescription('Hello World ' . time());
$ise->updateEndPoint($ep);

$dev = $ise->findNetworkDevice('search string');
```

