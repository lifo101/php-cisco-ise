<?php

namespace Lifo\CiscoISE;

use IteratorAggregate;

/**
 * Search result from ISE Client. Contains a list of results. Will automatically paginate the results, as needed.
 */
class SearchResult extends AbstractResult implements IteratorAggregate
{
    private int  $total;
    private int  $limit;
    private bool $hydrate;

    public function __construct(object $res, CiscoISEClient $ise)
    {
        parent::__construct($res, $ise);
        $this->total = $this->extractProperty('total', $this->result, 0);
        $this->limit = 0;
        $this->hydrate = false;
    }

    public function getIterator(): CiscoISEIterator
    {
        $list = $this->getResources();
        $next = $this->getNextPage();
        $it = new CiscoISEIterator($this->ise, $this->total, $list, $next ? $next->href : null, $this->hydrate);
        if ($this->limit) {
            $it->setLimit($this->limit);
        }
        return $it;
    }

    /**
     * Get total items in result
     *
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * Return the current resource set.
     *
     * @return object[]|null
     */
    public function getResources(): ?array
    {
        return $this->extractProperty('resources', $this->result);
    }

    /**
     * Return the 'next page' object
     *
     * @param bool $hrefOnly Only return the HREF property
     *
     * @return mixed
     */
    public function getNextPage($hrefOnly = false)
    {
        $next = $this->extractProperty('nextPage', $this->result);
        if ($hrefOnly && $next) {
            $next = $next->href;
        }
        return $next;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     *
     * @return SearchResult
     */
    public function setLimit(?int $limit): SearchResult
    {
        $this->limit = $limit ?? 0;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHydrate(): bool
    {
        return $this->hydrate;
    }

    /**
     * @param bool $hydrate
     *
     * @return SearchResult
     */
    public function setHydrate(bool $hydrate): SearchResult
    {
        $this->hydrate = $hydrate;

        return $this;
    }
}