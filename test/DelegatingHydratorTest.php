<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator;

use ArrayObject;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Zend\Hydrator\DelegatingHydrator;
use Zend\Hydrator\HydratorInterface;

/**
 * Unit tests for {@see DelegatingHydrator}
 *
 * @covers \Zend\Hydrator\DelegatingHydrator
 */
class DelegatingHydratorTest extends TestCase
{
    /**
     * @var DelegatingHydrator
     */
    protected $hydrator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $hydrators;

    /**
     * @var ArrayObject
     */
    protected $object;

    /**
     * {@inheritDoc}
     */
    protected function setUp() : void
    {
        $this->hydrators = $this->prophesize(ContainerInterface::class);
        $this->hydrator = new DelegatingHydrator($this->hydrators->reveal());
        $this->object = new ArrayObject;
    }

    public function testExtract()
    {
        $hydrator = $this->prophesize(HydratorInterface::class);
        $hydrator->extract($this->object)->willReturn(['foo' => 'bar']);

        $this->hydrators->has(Argument::type(ArrayObject::class))->willReturn(true);
        $this->hydrators->get(ArrayObject::class)->willReturn($hydrator->reveal());

        $this->assertEquals(['foo' => 'bar'], $this->hydrator->extract($this->object));
    }

    public function testHydrate()
    {
        $hydrator = $this->prophesize(HydratorInterface::class);
        $hydrator->hydrate(['foo' => 'bar'], $this->object)->willReturn($this->object);

        $this->hydrators->has(Argument::type(ArrayObject::class))->willReturn(true);
        $this->hydrators->get(ArrayObject::class)->willReturn($hydrator->reveal());

        $this->assertEquals($this->object, $this->hydrator->hydrate(['foo' => 'bar'], $this->object));
    }
}
