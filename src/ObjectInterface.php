<?php


namespace Lifo\CiscoISE;


interface ObjectInterface
{
    /**
     * Create a new Object based on the $from properties given and the current static object context
     *
     * @param mixed   $from
     * @param ?object $dest
     *
     * @return mixed
     */
    public static function createFrom(mixed $from, ?object $dest = null): mixed;

    /**
     * Return the ID of the object
     *
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * Serialize the object into an array for server digestion
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Map the class property name to a valid JSON key that the server would expect
     *
     * @param string $prop
     *
     * @return string
     */
    public function mapPropToKey(string $prop): string;
}