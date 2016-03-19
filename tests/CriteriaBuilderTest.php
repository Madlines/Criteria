<?php

namespace Madlines\Criteria\Tests;

use Madlines\Criteria\Criteria;
use Madlines\Criteria\CriteriaBuilder;
use Madlines\Criteria\Criterion;
use Madlines\Criteria\Tests\Mock\ExtendedCriteriaMockWithAllowedKeysSet;

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

        $criteria = $builder->buildFromArray($input, new Criteria());

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

    public function testParsingToCriteriaWithAllowedKeysSet()
    {
        $builder = $this->getBuilder();

        $input = [
            'foo__eq' => 'Cat',
            'lorem' => 'Dog',
            'dolor__starts_with' => 'Bird',
        ];

        $criteria = $builder->buildFromArray($input, new ExtendedCriteriaMockWithAllowedKeysSet());

        $keys = $criteria->getKeys();
        $this->assertCount(2, $keys);
        $this->assertContains('foo', $keys);
        $this->assertContains('dolor', $keys);

        $all = $criteria->getAll();
        $this->assertContains(new Criterion\Equals('foo', 'Cat'), $all, '', false, false);
        $this->assertContains(new Criterion\StartsWith('dolor', 'Bird'), $all, '', false, false);
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

        $builder->buildFromArray($input, new Criteria());
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

        $builder->buildFromArray($input, new Criteria());
    }

    public function testUsingAMappingStrategy()
    {
        $builder = new CriteriaBuilder();
        $builder->setMappingStrategy(function ($operator) {
            if ('eq' === $operator) {
                return Criterion\Equals::class;
            }

            if ('contains' === $operator) {
                return Criterion\Contains::class;
            }

            return '';
        });

        $input = [
            'foo__eq' => 'Cat',
            'bar__contains' => 'Dog',
        ];

        $criteria = $builder->buildFromArray($input, new Criteria());

        $all = $criteria->getAll();
        $this->assertCount(2, $all);
        $this->assertContains(new Criterion\Equals('foo', 'Cat'), $all, '', false, false);
        $this->assertContains(new Criterion\Contains('bar', 'Dog'), $all, '', false, false);
    }
}
