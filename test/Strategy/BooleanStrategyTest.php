<?php

/**
 * @see       https://github.com/laminas/laminas-hydrator for the canonical source repository
 * @copyright https://github.com/laminas/laminas-hydrator/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace LaminasTest\Hydrator\Strategy;

use Laminas\Hydrator\Exception\InvalidArgumentException;
use Laminas\Hydrator\Strategy\BooleanStrategy;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see BooleanStrategy}
 *
 * @covers \Laminas\Hydrator\Strategy\BooleanStrategy
 */
class BooleanStrategyTest extends TestCase
{
    public function testConstructorWithValidInteger()
    {
        $this->assertInstanceOf(BooleanStrategy::class, new BooleanStrategy(1, 0));
    }

    public function testConstructorWithValidString()
    {
        $this->assertInstanceOf(BooleanStrategy::class, new BooleanStrategy('true', 'false'));
    }

    public function testExceptionOnWrongTrueValueInConstructor()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected int or string as $trueValue.');

        new BooleanStrategy(true, 0);
    }

    public function testExceptionOnWrongFalseValueInConstructor()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected int or string as $falseValue.');

        new BooleanStrategy(1, false);
    }

    public function testExtractString()
    {
        $hydrator = new BooleanStrategy('true', 'false');
        $this->assertEquals('true', $hydrator->extract(true));
        $this->assertEquals('false', $hydrator->extract(false));
    }

    public function testExtractInteger()
    {
        $hydrator = new BooleanStrategy(1, 0);

        $this->assertEquals(1, $hydrator->extract(true));
        $this->assertEquals(0, $hydrator->extract(false));
    }

    public function testExtractThrowsExceptionOnUnknownValue()
    {
        $hydrator = new BooleanStrategy(1, 0);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to extract');

        $hydrator->extract(5);
    }

    public function testHydrateString()
    {
        $hydrator = new BooleanStrategy('true', 'false');
        $this->assertEquals(true, $hydrator->hydrate('true'));
        $this->assertEquals(false, $hydrator->hydrate('false'));
    }

    public function testHydrateInteger()
    {
        $hydrator = new BooleanStrategy(1, 0);
        $this->assertEquals(true, $hydrator->hydrate(1));
        $this->assertEquals(false, $hydrator->hydrate(0));
    }

    public function testHydrateBool()
    {
        $hydrator = new BooleanStrategy(1, 0);
        $this->assertEquals(true, $hydrator->hydrate(true));
        $this->assertEquals(false, $hydrator->hydrate(false));
    }

    public function testHydrateUnexpectedValueThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unexpected value');
        $hydrator = new BooleanStrategy(1, 0);
        $hydrator->hydrate(2);
    }

    public function testHydrateInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to hydrate');
        $hydrator = new BooleanStrategy(1, 0);
        $hydrator->hydrate(new \stdClass());
    }
}