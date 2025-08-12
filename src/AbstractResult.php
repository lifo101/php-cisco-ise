<?php


namespace Lifo\CiscoISE;

abstract class AbstractResult
{
    protected CiscoISEClient $ise;
    protected object         $result;

    public function __construct(object $result, CiscoISEClient $ise)
    {
        $this->result = $result;
        $this->ise = $ise;
    }

    /**
     * Return the RAW result response from the server.
     */
    protected function getResult(): object
    {
        return $this->result;
    }

    protected function getISEClient(): CiscoISEClient
    {
        return $this->ise;
    }

    protected function extractProperty(string $var, object $obj, mixed $default = null): mixed
    {
        if (property_exists($obj, $var)) {
            return $obj->$var;
        }
        return $default;
    }

}