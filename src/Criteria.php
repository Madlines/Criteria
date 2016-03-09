<?php

namespace Madlines\Criteria;

class Criteria
{
    /**
     * @var Criterion[]
     */
    private $criteria = [];

    /**
     * @param Criterion $criterion
     * @return $this
     */
    public function add(Criterion $criterion)
    {
        $this->criteria[$criterion->getKey()] = $criterion;
        return $this;
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
