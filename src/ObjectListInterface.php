<?php


namespace Lifo\CiscoISE;


interface ObjectListInterface
{
    /**
     * Add an item
     *
     * @param mixed $item
     *
     * @return $this
     */
    public function add($item): self;

    /**
     * Remove an item
     *
     * @param mixed $item
     *
     * @return $this
     */
    public function remove($item): self;

    /**
     * Return true if the item exists
     *
     * @param mixed $item
     *
     * @return bool
     */
    public function exists($item): bool;

    /**
     * Returns the total items in the list
     *
     * @return int
     */
    public function count(): int;

    /**
     * Returns the first item in the list
     * @return mixed
     */
    public function first();
}