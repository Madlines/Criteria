<?php

namespace Madlines\Criteria;

class Criteria
{
    const DIR_ASC = 'ASC';
    const DIR_DESC = 'DESC';

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
     * @var string|null
     */
    private $sortBy;

    /**
     * @var string|null
     */
    private $sortDir;

    /**
     * @param Criterion $criterion
     * @return Criteria|mixed
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
     * @return Criterion
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

    /**
     * @param string $key
     * @param string $direction
     * @return Criteria
     * @throws \LogicException
     */
    public function setSorting($key, $direction)
    {
        if (self::DIR_ASC !== $direction && self::DIR_DESC !== $direction) {
            throw new \LogicException('Sorting direction has to be either ASC or DESC');
        }

        $criteria = clone $this;

        $criteria->sortBy = (string) $key;
        $criteria->sortDir = $direction;

        return $criteria;
    }

    /**
     * @return bool
     */
    public function isSortingSet()
    {
        return null !== $this->sortBy && null !== $this->sortDir;
    }

    /**
     * @return null|string
     */
    public function getSortingKey()
    {
        if (!$this->isSortingSet()) {
            throw new \LogicException('You have to setSorting first.');
        }

        return $this->sortBy;
    }

    /**
     * @return null|string
     */
    public function getSortingDirection()
    {
        if (!$this->isSortingSet()) {
            throw new \LogicException('You have to setSorting first.');
        }

        return $this->sortDir;
    }
}
