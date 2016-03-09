<?php

namespace Madlines\Criteria\Tests\Criterion;

use Madlines\Criteria\BaseCriterion;
use Madlines\Criteria\Criterion;
use Madlines\Criteria\Tests\BaseCriterionTest;

class StartsWithTest extends BaseCriterionTest
{
    /**
     * @return BaseCriterion|Criterion\StartsWith
     */
    protected function getCriterion($name, $value)
    {
        return new Criterion\StartsWith($name, $value);
    }
}
