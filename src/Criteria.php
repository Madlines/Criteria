<?php

namespace Madlines\Criteria;

class Criteria
{
    /**
     * @var Criterion[]
     */
    private $criteria = [];

    /**
     * @param Criterion[]
     */
    public function __construct($criteria = [])
    {
        foreach($criteria as $criterion) {
            if (!($criterion instanceof Criterion)) {
                throw new \InvalidArgumentException('Criteria must be build of set of Criterion objects or empty array.');
            }
        }

        $this->criteria = $criteria;
    }

    /**
     * @param Criterion $criterion
     * @return Criteria
     */
    public function add(Criterion $criterion)
    {
        if (!$this->canAdd($criterion)) {
            throw new \LogicException('A criterion you\'re trying to add contains a disallowed key');
        }

        $criteriaArray = $this->criteria;
        $criteriaArray[$criterion->getKey()] = $criterion;

        return new static($criteriaArray);
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
}
