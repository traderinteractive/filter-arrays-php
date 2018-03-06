<?php

namespace TraderInteractive\Filter;

use TraderInteractive\Exceptions\FilterException;

/**
 * A collection of filters for arrays.
 */
final class Arrays
{
    /**
     * Filter an array by throwing if not an array or count not in the min/max range.
     *
     * @param mixed   $value    The value to filter.
     * @param integer $minCount The minimum allowed count in the array.
     * @param integer $maxCount The maximum allowed count in the array.
     *
     * @return array
     *
     * @throws FilterException if $value is not an array
     * @throws FilterException if $value count is less than $minCount
     * @throws FilterException if $value count is greater than $maxCount
     */
    public static function filter($value, int $minCount = 1, int $maxCount = PHP_INT_MAX) : array
    {
        if (!is_array($value)) {
            throw new FilterException("Value '" . trim(var_export($value, true), "'") . "' is not an array");
        }

        $count = count($value);
        if ($count < $minCount) {
            throw new FilterException("\$value count of {$count} is less than {$minCount}");
        }

        if ($count > $maxCount) {
            throw new FilterException("\$value count of {$count} is greater than {$maxCount}");
        }

        return $value;
    }

    /**
     * Filter an array by throwing if $value is not in $haystack adhering to $strict.
     *
     * @param mixed $value    The searched value.
     * @param array $haystack The array to be searched.
     * @param bool  $strict   Flag to compare strictly or not. @see in_array()
     *
     * @return mixed The passed in value
     *
     * @throws FilterException if $value is not in array $haystack
     */
    public static function in($value, array $haystack, bool $strict = true)
    {
        if (!in_array($value, $haystack, $strict)) {
            throw new FilterException(
                "Value '" . trim(var_export($value, true), "'") . "' is not in array " . var_export($haystack, true)
            );
        }

        return $value;
    }

    /**
     * Given a multi-dimensional array, flatten the array to a single level.
     *
     * The order of the values will be maintained, but the keys will not.
     *
     * For example, given the array [[1, 2], [3, [4, 5]]], this would result in the array [1, 2, 3, 4, 5].
     *
     * @param array $value The array to flatten.
     *
     * @return array The single-dimension array.
     */
    public static function flatten(array $value) : array
    {
        $result = [];

        array_walk_recursive(
            $value,
            function ($item) use (&$result) {
                $result[] = $item;
            }
        );

        return $result;
    }
}
