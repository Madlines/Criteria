<?php

namespace Madlines\Criteria\Tests\Criterion;

use Madlines\Criteria\BaseCriterion;
use Madlines\Criteria\Criterion;
use Madlines\Criteria\Tests\BaseCriterionTest;

class BiggerThanTest extends BaseCriterionTest
{
    /**
     * @return BaseCriterion|Criterion\BiggerThan
     */
    protected function getCriterion($name, $value)
    {
        return new Criterion\BiggerThan($name, $value);
    }
}
