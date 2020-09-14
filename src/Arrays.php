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

    /**
     * Converts any non-array value to a single element array.
     *
     * @param mixed $value The value to convert.
     *
     * @return array The coverted array or the original value.
     */
    public static function arrayize($value) : array
    {
        if ($value === null) {
            return [];
        }

        if (!is_array($value)) {
            return [$value];
        }

        return $value;
    }


    /**
     * Copies values from the $source array into a new array using the $keyMap for destination keys.
     *
     * @param array[] $source The arrays with values to be copied.
     * @param array   $keyMap mapping of dest keys to source keys. If $keyMap is associative, the keys will be the
     *                        destination keys. If numeric the values will be the destination keys
     *
     * @return array
     */
    public static function copyEach(array $source, array $keyMap) : array
    {
        $result = [];
        foreach ($source as $sourceArray) {
            $result[] = self::copy($sourceArray, $keyMap);
        }

        return $result;
    }

    /**
     * Copies values from the $source array into a new array using the $keyMap for destination keys.
     *
     * @param array $source The array with values to be copied.
     * @param array $keyMap mapping of dest keys to source keys. If $keyMap is associative, the keys will be the
     *                      destination keys. If numeric the values will be the destination keys
     *
     * @return array
     */
    public static function copy(array $source, array $keyMap) : array
    {
        $result = [];
        foreach ($keyMap as $destinationKey => $sourceKey) {
            if (is_int($destinationKey)) {
                $destinationKey = $sourceKey;
            }

            if (array_key_exists($sourceKey, $source)) {
                $result[$destinationKey] = $source[$sourceKey];
            }
        }

        return $result;
    }
}
