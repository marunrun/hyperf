<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace HyperfTest\Di;

use Hyperf\Di\ClosureDefinitionCollector;
use Hyperf\Di\ReflectionType;
use HyperfTest\Di\Stub\Foo;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ClosureDefinitionTest extends TestCase
{
    public function testGetParameters()
    {
        $collector = new ClosureDefinitionCollector();
        $closure = \Closure::fromCallable([new Foo(), 'getBar']);
        $definitions = $collector->getParameters($closure);
        $this->assertEquals(4, count($definitions));
        $this->assertEquals('int', $definitions[0]->getName());
        $this->assertFalse($definitions[0]->getMeta('defaultValueAvailable'));
        $this->assertTrue($definitions[1]->getMeta('defaultValueAvailable'));
    }

    public function testGetReturnTypes()
    {
        $collector = new ClosureDefinitionCollector();
        $closure = \Closure::fromCallable([new Foo(), 'getBar']);
        $type = $collector->getReturnType($closure);
        $this->assertEquals('mixed', $type->getName());
    }

    public function testGetParameterOfNoType()
    {
        $collector = new ClosureDefinitionCollector();
        $closure = \Closure::fromCallable([new Foo(), 'getFoo']);
        /** @var ReflectionType[] $definitions */
        $definitions = $collector->getParameters($closure);
        $this->assertEquals(1, count($definitions));
        $this->assertEquals('mixed', $definitions[0]->getName());
    }
}
