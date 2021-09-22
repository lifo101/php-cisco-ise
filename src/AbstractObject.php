<?php


namespace Lifo\CiscoISE;


use Exception;
use JsonSerializable;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractObject implements ObjectInterface, JsonSerializable
{
    /**
     * AbstractObject constructor.
     *
     * @param object|array $from
     */
    public function __construct($from = null)
    {
        if ($from) self::createFrom($from, $this);
    }

    public function getId(): ?string
    {
        return null;
    }

    public function mapPropToKey(string $prop): string
    {
        return $prop;
    }

    public function toArray(): array
    {
        $ary = [];
        $props = get_object_vars($this);
        foreach ($props as $prop => $val) {
            $key = $this->mapPropToKey($prop);
            if ($val instanceof ObjectInterface) {
                $ary[$key] = $val->toArray();
            } else {
                $ary[$key] = $val;
            }
        }
        return $ary;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @param mixed $from
     * @param mixed $dest
     *
     * @return self
     */
    public static function createFrom($from, $dest = null): self
    {
        $dest ??= new static();
        if (is_array($from)) $from = (object)$from;
        $props = get_object_vars($from);
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($props as $prop => $value) {
            try {
                if (is_object($value)) {
                    // some server objects are lowercase; must uppercase it for Class detection to work
                    $cls = __NAMESPACE__ . '\\' . str_replace('setting', 'Setting', ucfirst($prop));
                    if (is_a($cls, ObjectInterface::class, true)) {
                        $value = new $cls($value);
                    }
                }
                if ($accessor->isWritable($dest, $prop)) {
                    $accessor->setValue($dest, $prop, $value);
                }
            } catch (Exception $e) {
                continue;
            }
        }
        return $dest;
    }
}