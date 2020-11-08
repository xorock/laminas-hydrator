<?php

/**
 * @see       https://github.com/laminas/laminas-hydrator for the canonical source repository
 * @copyright https://github.com/laminas/laminas-hydrator/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Laminas\Hydrator\Strategy;

use Laminas\Hydrator\Exception\InvalidArgumentException;

use function get_class;
use function gettype;
use function is_bool;
use function is_int;
use function is_object;
use function is_string;
use function sprintf;

/**
 * This Strategy extracts and hydrates int and string values to Boolean values
 */
final class BooleanStrategy implements StrategyInterface
{
    /**
     * @var int|string
     */
    private $trueValue;

    /**
     * @var int|string
     */
    private $falseValue;

    /**
     * @param int|string $trueValue
     * @param int|string $falseValue
     * @throws InvalidArgumentException
     */
    public function __construct($trueValue, $falseValue)
    {
        if (! is_int($trueValue) && ! is_string($trueValue)) {
            throw new InvalidArgumentException(sprintf(
                'Unable to instantiate BooleanStrategy. Expected int or string as $trueValue. %s was given',
                is_object($trueValue) ? get_class($trueValue) : gettype($trueValue)
            ));
        }

        if (! is_int($falseValue) && ! is_string($falseValue)) {
            throw new InvalidArgumentException(sprintf(
                'Unable to instantiate BooleanStrategy. Expected int or string as $falseValue. %s was given',
                is_object($falseValue) ? get_class($falseValue) : gettype($falseValue)
            ));
        }

        $this->trueValue  = $trueValue;
        $this->falseValue = $falseValue;
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param  bool|null $value The original value.
     * @throws InvalidArgumentException
     * @return int|string|null Returns the value that should be extracted.
     */
    public function extract($value, ?object $object = null)
    {
        if (null === $value) {
            return $value;
        }

        if (! is_bool($value)) {
            throw new InvalidArgumentException(sprintf(
                'Unable to extract. Expected bool. %s was given.',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        return $value === true ? $this->trueValue : $this->falseValue;
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param  bool|int|string|null $value The original value.
     * @throws InvalidArgumentException
     * @return bool|null Returns the value that should be hydrated.
     */
    public function hydrate($value, ?array $data = null)
    {
        if (null === $value) {
            return $value;
        }

        if (is_bool($value)) {
            return $value;
        }

        if (! is_string($value) && ! is_int($value)) {
            throw new InvalidArgumentException(sprintf(
                'Unable to hydrate. Expected bool, string or int. %s was given.',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        if ($value === $this->trueValue) {
            return true;
        }

        if ($value === $this->falseValue) {
            return false;
        }

        throw new InvalidArgumentException(sprintf(
            'Unexpected value %s can\'t be hydrated. Expect %s or %s as Value.',
            $value,
            $this->trueValue,
            $this->falseValue
        ));
    }
}
