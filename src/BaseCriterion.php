<?php

namespace Madlines\Criteria;

abstract class BaseCriterion implements Criterion
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var mixed
     */
    private $value;

    /**
     * BaseCriterion constructor.
     * @param string $fieldName
     * @param mixed $value
     */
    public function __construct($fieldName, $value)
    {
        $this->fieldName = (string) $fieldName;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->fieldName;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
