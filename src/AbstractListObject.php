<?php


namespace Lifo\CiscoISE;


use JsonSerializable;

abstract class AbstractListObject implements ObjectInterface, ObjectListInterface, JsonSerializable
{
    protected array $list = [];

    public function __construct(?array $from = null)
    {
        if ($from) self::createFrom($from, $this);
    }

    public static function createFrom($from, $dest = null): self
    {
        $dest ??= new static();
        if (!is_array($from)) return $dest;
        foreach ($from as $item) {
            $dest->add($item);
        }
        return $dest;
    }

    public function mapPropToKey(string $prop): string
    {
        return $prop;
    }

    public function toArray(): array
    {
        return $this->list;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Add an item to the list
     *
     * @param string $item
     *
     * @return $this
     */
    public function add($item): self
    {
        if (!$this->exists($item)) {
            $this->list[] = $item;
        }

        return $this;
    }

    /**
     * Remove an item from the list
     *
     * @param string $item
     *
     * @return $this
     */
    public function remove($item): self
    {
        $this->list = array_values(array_filter($this->list, fn($ele) => $ele !== $item));

        return $this;
    }

    /**
     * Returns true if the item exists
     *
     * @param string $item
     *
     * @return bool
     */
    public function exists($item): bool
    {
        return in_array($item, $this->list);
    }

    public function count(): int
    {
        return count($this->list);
    }

    public function first()
    {
        if (!$this->list) return null;
        return $this->list[0];
    }

    public function getId(): ?string
    {
        return null;
    }
}