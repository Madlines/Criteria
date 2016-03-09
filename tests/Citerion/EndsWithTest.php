<?php

namespace Madlines\Criteria\Tests\Criterion;

use Madlines\Criteria\BaseCriterion;
use Madlines\Criteria\Criterion;
use Madlines\Criteria\Tests\BaseCriterionTest;

class EndsWithTest extends BaseCriterionTest
{
    /**
     * @return BaseCriterion|Criterion\EndsWith
     */
    protected function getCriterion($name, $value)
    {
        return new Criterion\EndsWith($name, $value);
    }
}
