<?php

namespace Madlines\Criteria\Tests\MappingStrategy;

use Madlines\Criteria\MappingStrategy\DefaultMappingStrategy;

class DefaultMappingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testIfStrategyReturnsProperClassName()
    {
        $strategy = new DefaultMappingStrategy();

        $this->assertEquals(
           '\Madlines\Criteria\Criterion\Equals',
            $strategy('equals')
        );

        $this->assertEquals(
            '\Madlines\Criteria\Criterion\Foo',
            $strategy('foo')
        );

        $this->assertEquals(
            '\Madlines\Criteria\Criterion\LoremIpsum',
            $strategy('lorem_ipsum')
        );
    }
}
