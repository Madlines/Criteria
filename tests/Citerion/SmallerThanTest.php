<?php

namespace Madlines\Criteria\Tests\Criterion;

use Madlines\Criteria\BaseCriterion;
use Madlines\Criteria\Criterion;
use Madlines\Criteria\Tests\BaseCriterionTest;

class SmallerThanTest extends BaseCriterionTest
{
    /**
     * @return BaseCriterion|Criterion\SmallerThan
     */
    protected function getCriterion($name, $value)
    {
        return new Criterion\SmallerThan($name, $value);
    }
}
