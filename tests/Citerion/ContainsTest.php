<?php

namespace Madlines\Criteria\Tests\Criterion;

use Madlines\Criteria\BaseCriterion;
use Madlines\Criteria\Criterion;
use Madlines\Criteria\Tests\BaseCriterionTest;

class ContainsTest extends BaseCriterionTest
{
    /**
     * @return BaseCriterion|Criterion\Contains
     */
    protected function getCriterion($name, $value)
    {
        return new Criterion\Contains($name, $value);
    }
}
