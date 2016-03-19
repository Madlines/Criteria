<?php

namespace Madlines\Criteria;

class CriteriaBuilder
{
    const OPERATOR_SEPARATOR = '__';

    /**
     * @var string[]
     */
    private $map = [];

    /**
     * @var string|null
     */
    private $default;

    /**
     * @var callable|null
     */
    private $mappingStrategy;

    /**
     * @param string $name
     * @param string $criterionClassName
     * @return $this
     */
    public function map($name, $criterionClassName)
    {
        $this->map[(string) $name] = (string) $criterionClassName;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setDefault($name)
    {
        $this->default = (string) $name;
        return $this;
    }

    /**
     * @param array $criteriaArray
     * @param Criteria $criteria
     * @return Criteria
     */
    public function buildFromArray(array $criteriaArray, Criteria $criteria)
    {
        foreach ($criteriaArray as $key => $value) {
            list ($keyName, $className) = $this->getKeyNameAndCriterionClassName($key);

            $criterion = new $className($keyName, $value);

            if ($criteria->canAdd($criterion)) {
                $criteria = $criteria->add($criterion);
            }
        }

        return $criteria;
    }

    /**
     * @param string $key
     * @return string[] [$keyName, $className]
     */
    private function getKeyNameAndCriterionClassName($key)
    {
        $chunks = explode(self::OPERATOR_SEPARATOR, $key);

        if (2 < count($chunks)) {
            throw new \LogicException('$key has to look like eg "foo" or "foo__equals" ("{keyName}" or "{keyName}__{operator}")');
        }

        $keyName = $chunks[0];

        if (\array_key_exists(1, $chunks)) {
            $operator = $chunks[1];
        } else {
            if (!$this->default) {
                throw new \LogicException('$key has to look like eg "foo__equals" ("{keyName}__{operator}") if you didn\'t set any default operator.');
            }

            $operator = $this->default;
        }

        if (!\array_key_exists($operator, $this->map)) {
            $className = $this->generateClassName($operator);
            if (!$className) {
                throw new \LogicException('There is no className mapped to ' . $operator);
            }
        } else {
            $className = $this->map[$operator];
        }

        return [$keyName, $className];
    }

    /**
     * @param string $operator
     * @return string full classname or empty string
     */
    public function generateClassName($operator)
    {
        if ($this->mappingStrategy) {
            $callback = $this->mappingStrategy;
            return $callback($operator);
        }

        return '';
    }

    /**
     * @param callable $strategy
     * @return $this
     */
    public function setMappingStrategy(callable $strategy)
    {
        $this->mappingStrategy = $strategy;
        return $this;
    }
}
