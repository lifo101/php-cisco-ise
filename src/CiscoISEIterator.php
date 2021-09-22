<?php


namespace Lifo\CiscoISE;


class CiscoISEIterator implements \Iterator
{
    /** @var CiscoISEClient */
    private $client;
    /** @var int Current page number */
    private $page;
    /** @var int Position in current result array [0 .. count(results)] */
    private $pos;
    /** @var int Overall index value [0 .. totalElements] */
    private $index;
    /** @var array Current result page */
    private $results;
    /** @var bool Only set in constructor to prevent {@link load} from being called twice at startup */
    private $loaded;
    /** @var int */
    private $total;
    /** @var int */
    private $limit;
    /** @var string|null */
    private $next;
    /**
     * @var bool
     */
    private $hydrate;

    /**
     * @param CiscoISEClient $client
     * @param int            $total
     * @param array          $results Initial set of results
     * @param string         $next    URL for next result page
     * @param bool           $hydrate Hydrate results if true
     */
    public function __construct(CiscoISEClient $client, $total, $results = null, $next = null, $hydrate = false)
    {
        $this->page = 0;
        $this->pos = 0;
        $this->index = 0;
        $this->client = clone $client;
        $this->total = $total;
        $this->limit = 0;
        $this->results = $results ?: [];
        $this->next = $next;
        $this->hydrate = $hydrate;
        if ($results === null) {
            $this->load();
        }
        $this->loaded = true;
    }

    /**
     * Return the current element
     *
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        $o = $this->results[$this->pos];
        if ($this->hydrate && isset($o->link->href) && $o->link->rel === 'self') {
            $o = $this->client->get($o->link->href);
        }
        return $o;
    }

    /**
     * Move forward to next element
     *
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->pos++;
        $this->index++;
        if (!isset($this->results[$this->pos])) {
            $this->page++;
            $this->load();
        }
    }

    /**
     * Return the key of the current element
     *
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Checks if current position is valid
     *
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->results[$this->pos]) && (!$this->limit || $this->index < $this->limit);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        if (!$this->loaded) {
            $this->page = 0;
            $this->index = 0;
            $this->pos = 0;
            $this->results = [];
            $this->load();
        }
        $this->loaded = false;
    }

    /**
     * Load the next batch of results
     */
    private function load()
    {
        $this->pos = 0;
        if ($this->next && $this->total > 0 && $this->index < $this->total && (!$this->limit || $this->index < $this->limit)) {
            $res = $this->client->get($this->next);
            if ($res instanceof SearchResult) {
                $this->results = $res->getResources();
                $this->next = $res->getNextPage(true);
            }

        } else {
            $this->results = [];
        }
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }
}