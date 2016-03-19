<?php

namespace Madlines\Criteria;

class Criteria
{
    /**
     * @var Criterion[]
     */
    private $criteria = [];

    /**
     * @var int|null
     */
    private $page;

    /**
     * @var int|null
     */
    private $resultsPerPage;

    /**
     * @param Criterion $criterion
     * @return Criteria
     */
    public function add(Criterion $criterion)
    {
        if (!$this->canAdd($criterion)) {
            throw new \LogicException('A criterion you\'re trying to add contains a disallowed key');
        }

        $criteria = clone $this;
        $criteria->criteria[$criterion->getKey()] = $criterion;

        return $criteria;
    }

    /**
     * @param Criterion $criterion
     * @return bool
     */
    public function canAdd(Criterion $criterion)
    {
        $allowedKeys = $this->getAllowedKeys();
        if (empty($allowedKeys)) {
            return true;
        }

        return in_array($criterion->getKey(), $allowedKeys);
    }

    /**
     * @return Criterion[]
     */
    public function getAll()
    {
        return $this->criteria;
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return \array_keys($this->criteria);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function keyExistsFor($key)
    {
         return \array_key_exists($key, $this->criteria);
    }

    /**
     * @param string $key
     * @return Criterion[]
     */
    public function getFor($key)
    {
         if (!$this->keyExistsFor($key)) {
             throw new \LogicException('There is no criterion set for key ' . $key);
         }

        return $this->criteria[$key];
    }

    /**
     * Returns a list of allowed keys or empty array (which means: all allowed)
     *
     * @return array|string[]
     */
    public function getAllowedKeys()
    {
        return [];
    }

    /**
     * @param int $page
     * @param int $resultsPerPage
     * @return Criteria
     */
    public function setPagination($page, $resultsPerPage)
    {
        $criteria = clone $this;

        $criteria->page = (int) $page;
        $criteria->resultsPerPage = (int) $resultsPerPage;

        return $criteria;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        if (null === $this->page) {
            throw new \LogicException('You have to setPagination first.');
        }

        return $this->page;
    }

    /**
     * @return int
     */
    public function getResultsPerPage()
    {
        if (null === $this->resultsPerPage) {
            throw new \LogicException('You have to setPagination first.');
        }

        return $this->resultsPerPage;
    }

    /**
     * @return bool
     */
    public function isPaginationSet()
    {
        return (bool) $this->page && (bool) $this->resultsPerPage;
    }
}
