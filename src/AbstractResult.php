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
     *
     * @return object
     */
    protected function getResult()
    {
        return $this->result;
    }

    /**
     * @return CiscoISEClient
     */
    protected function getISEClient()
    {
        return $this->ise;
    }

    /**
     * @param string $var
     * @param object $obj
     * @param mixed  $default
     *
     * @return mixed
     */
    protected function extractProperty(string $var, object $obj, $default = null)
    {
        if (property_exists($obj, $var)) {
            return $obj->$var;
        }
        return $default;
    }

}