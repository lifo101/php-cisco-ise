<?php

namespace Lifo\CiscoISE;


use OutOfBoundsException;
use DateTime;
use InvalidArgumentException;

trait ExceptionsTrait
{
    /**
     * Creates an {@link InvalidArgumentException} with a backtrace that references the actual caller from user code.
     *
     * @param array  $args  Function arguments to serialize into the exception message for readability.
     * @param string $label Message prefix
     * @param int    $limit Stack trace limit
     *
     * @return InvalidArgumentException
     */
    protected static function createInvalidArgumentException(?array $args = null, $label = 'Invalid argument provided', $limit = 2): InvalidArgumentException
    {
        $t = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $limit);
        $t = end($t);
        if ($t) {
            return new InvalidArgumentException(sprintf("%s in function %s%s%s(%s) in file %s on line %s",
                $label,
                $t['class'],
                $t['type'],
                $t['function'],
                self::serializeArguments($args),
                $t['file'],
                $t['line']
            ));
        }
        return new InvalidArgumentException($label);
    }

    /**
     * Creates an {@link OutOfBoundsException} with a backtrace that references the actual caller from user code.
     *
     * @param        $value
     * @param string $label
     *
     * @return OutOfBoundsException
     */
    protected static function createOutOfBoundsException($value, $label = 'Invalid value'): OutOfBoundsException
    {
        $t = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $t = end($t);
        if ($t) {
            return new OutOfBoundsException(sprintf("%s to %s%s%s(%s) in file %s on line %s",
                $label,
                $t['class'],
                $t['type'],
                $t['function'],
                self::serializeArguments([$value]),
                $t['file'],
                $t['line']
            ));
        }
        return new OutOfBoundsException($label);
    }

    protected static function serializeArguments($args, $limit = 32): string
    {
        $list = [];
        if ($args) {
            $i = 0;
            foreach ($args as $a) {
                $i++;
                $list[] = match (true) {
                    $a === null => "NULL",
                    is_numeric($a) => $a,
                    is_array($a) => sprintf('{array[%d]}', count($a)),
                    is_object($a) && method_exists($a, '__toString'), is_string($a) => '"' . (strlen($a) > $limit ? substr($a, 0, $limit) . '...' : $a) . '"',
                    $a instanceof DateTime => sprintf('new DateTime("%s")', $a->format(DATE_RFC3339)),
                    is_callable($a) => '{callable}',
                    is_resource($a) => '{' . get_resource_type($a) . '}',
                    default => "{arg$i}",
                };
            }
        }
        return $list ? implode(', ', $list) : '';
    }

}
