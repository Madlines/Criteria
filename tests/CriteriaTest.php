<?php

namespace Madlines\Criteria\Tests;

use Madlines\Criteria\BaseCriterion;
use Madlines\Criteria\Criteria;
use Madlines\Criteria\Criterion;
use Madlines\Criteria\Tests\Mock\ExtendedCriteriaMockWithAllowedKeysSet;
use Madlines\Criteria\Tests\Mock\ExtendedCriteriaMockWithEmptyListAllowedKeysSet;

class CriteriaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $name
     * @param mixed $value
     * @return \PHPUnit_Framework_MockObject_MockObject|Criterion
     */
    private function getCriterionMock($name, $value)
    {
        $mock = $this->getMockBuilder(BaseCriterion::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('getKey')->willReturn($name);
        $mock->method('getValue')->willReturn($value);

        return $mock;
    }

    public function testAddingAndGettingCriteria()
    {
        $criterion1 = $this->getCriterionMock('foo', 'bar');
        $criterion2 = $this->getCriterionMock('lorem', 'ipsum');
        $criterion3 = $this->getCriterionMock('dolor', 'amet');
        $criterion4 = $this->getCriterionMock('dolor', 'vinco');

        $criteria = (new Criteria())
            ->add($criterion1)
            ->add($criterion2)
            ->add($criterion3)
            ->add($criterion4);

        $all = $criteria->getAll();
        $this->assertCount(3, $all);
        $this->assertContains($criterion1, $all);
        $this->assertContains($criterion2, $all);
        $this->assertContains($criterion4, $all);

        $this->assertEquals('bar', $criteria->getFor('foo')->getValue());
        $this->assertEquals('ipsum', $criteria->getFor('lorem')->getValue());
        $this->assertEquals('vinco', $criteria->getFor('dolor')->getValue());

        $keys = $criteria->getKeys();
        $this->assertCount(3, $keys) ;
        $this->assertContains('foo', $keys);
        $this->assertContains('lorem', $keys);
        $this->assertContains('dolor', $keys);
    }

    public function testCheckingForKeyExistence()
    {
        $criterion1 = $this->getCriterionMock('foo', 'bar');
        $criterion2 = $this->getCriterionMock('lorem', 'ipsum');

        $criteria = (new Criteria())
            ->add($criterion1)
            ->add($criterion2);

        $this->assertTrue($criteria->keyExistsFor('foo'));
        $this->assertTrue($criteria->keyExistsFor('lorem'));
        $this->assertFalse($criteria->keyExistsFor('ipsum'));
        $this->assertFalse($criteria->keyExistsFor('dolor'));
    }

    public function testIfExtendedCriteriaWithAllowedKeysSetVerifyKeysProperly()
    {
        $criterion1 = $this->getCriterionMock('foo', 'bar');
        $criterion2 = $this->getCriterionMock('lorem', 'ipsum');
        $criterion3 = $this->getCriterionMock('dolor', 'amet');

        $extendedCriteria = new ExtendedCriteriaMockWithAllowedKeysSet();

        $this->assertTrue($extendedCriteria->canAdd($criterion1));
        $this->assertFalse($extendedCriteria->canAdd($criterion2));
        $this->assertTrue($extendedCriteria->canAdd($criterion3));
    }

    /**
     * @expectedException \LogicException
     */
    public function testIfExtendedCriteriaWithAllowedKeysSetBlocksDisallowedKeys()
    {
        $criterion1 = $this->getCriterionMock('foo', 'bar');
        $criterion2 = $this->getCriterionMock('lorem', 'ipsum');

        $extendedCriteria = new ExtendedCriteriaMockWithAllowedKeysSet();

        $extendedCriteria
            ->add($criterion1)
            ->add($criterion2); // this should throw exception
    }

    public function testIfExtendedCriteriaWithAllowedKeysSetToEmptyArrayAllowsAddingEveryKey()
    {
        $criterion1 = $this->getCriterionMock('foo', 'bar');
        $criterion2 = $this->getCriterionMock('lorem', 'ipsum');
        $criterion3 = $this->getCriterionMock('dolor', 'amet');

        $extendedCriteria = new ExtendedCriteriaMockWithEmptyListAllowedKeysSet();
        $extendedCriteria = $extendedCriteria
            ->add($criterion1)
            ->add($criterion2)
            ->add($criterion3);

        $keys = $extendedCriteria->getKeys();
        $this->assertCount(3, $keys) ;
        $this->assertContains('foo', $keys);
        $this->assertContains('lorem', $keys);
        $this->assertContains('dolor', $keys);
    }
}
