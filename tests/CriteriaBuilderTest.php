<?php

namespace Madlines\Criteria\Tests;

use Madlines\Criteria\Criteria;
use Madlines\Criteria\CriteriaBuilder;
use Madlines\Criteria\Criterion;

class CriteriaBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return CriteriaBuilder
     */
    private function getBuilder()
    {
        $builder = new CriteriaBuilder();
        $builder->map('eq', Criterion\Equals::class);
        $builder->map('starts_with', Criterion\StartsWith::class);
        $builder->map('ends_with', Criterion\EndsWith::class);
        $builder->map('contains', Criterion\Contains::class);
        $builder->setDefault('eq');

        return $builder;
    }

    public function testParsingAQueryArray()
    {
        $builder = $this->getBuilder();

        $input = [
            'foo__eq' => 'Cat',
            'lorem' => 'Dog',
            'ipsum__starts_with' => 'Bird',
            'dolor__starts_with' => 'Snake', // That one will get overriden
            'dolor__ends_with' => 'Hedgehog',
        ];

        $criteria = new Criteria();
        $builder->buildFromArray($input, $criteria);

        $keys = $criteria->getKeys();
        $this->assertCount(4, $keys);
        $this->assertContains('foo', $keys);
        $this->assertContains('lorem', $keys);
        $this->assertContains('ipsum', $keys);
        $this->assertContains('dolor', $keys);

        $all = $criteria->getAll();
        $this->assertContains(new Criterion\Equals('foo', 'Cat'), $all, '', false, false);
        $this->assertContains(new Criterion\Equals('lorem', 'Dog'), $all, '', false, false);
        $this->assertContains(new Criterion\StartsWith('ipsum', 'Bird'), $all, '', false, false);
        $this->assertContains(new Criterion\EndsWith('dolor', 'Hedgehog'), $all, '', false, false);
    }

    /**
     * @expectedException \LogicException
     */
    public function testPassingFieldWithUnmappedOperator()
    {
        $builder = $this->getBuilder();

        $input = [
            'foo__neq' => 'Cat',
        ];

        $criteria = new Criteria();
        $builder->buildFromArray($input, $criteria);
    }

    /**
     * @expectedException \LogicException
     */
    public function testUsingDefaultWhenThereIsNoDefaultSet()
    {
        $builder = new CriteriaBuilder();
        $builder->map('eq', Criterion\Equals::class);

        $input = [
            'foo' => 'Cat',
        ];

        $criteria = new Criteria();
        $builder->buildFromArray($input, $criteria);
    }
}
