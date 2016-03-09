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
        $criteriaArray = $this->criteria;
        $criteriaArray[$criterion->getKey()] = $criterion;

        return new Criteria($criteriaArray);
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
}
