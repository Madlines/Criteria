<?php

namespace Madlines\Criteria\Tests;

use Madlines\Criteria\BaseCriterion;
use Madlines\Criteria\Criterion;

abstract class BaseCriterionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return BaseCriterion
     */
    abstract protected function getCriterion($name, $value);

    public function testGetters()
    {
       $criterion = $this->getCriterion('foo', 'bar');

        $this->assertEquals('foo', $criterion->getKey());
        $this->assertEquals('bar', $criterion->getValue());
    }
}
