<?php

namespace TraderInteractive\Filter;

use InvalidArgumentException;
use TraderInteractive\Exceptions\FilterException;
use TraderInteractive\Filter\Exceptions\DuplicateValuesException;

/**
 * A collection of filters for arrays.
 */
final class Arrays
{
    /**
     * @var int
     */
    const ARRAY_PAD_END = 1;

    /**
     * @var int
     */
    const ARRAY_PAD_FRONT = 2;

    /**
     * @var int
     */
    const ARRAY_UNIQUE_SORT_REGULAR = \SORT_REGULAR;

    /**
     * @var int
     */
    const ARRAY_UNIQUE_SORT_NUMERIC = \SORT_NUMERIC;

    /**
     * @var int
     */
    const ARRAY_UNIQUE_SORT_STRING = \SORT_STRING;

    /**
     * @var int
     */
    const ARRAY_UNIQUE_SORT_LOCALE_STRING = \SORT_LOCALE_STRING;

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

        $callBack = function ($item) use (&$result) {
            $result[] = $item;
        };

        array_walk_recursive($value, $callBack);

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

    /**
     * Pad array to the specified length with a value. Padding optionally to the front or end of the array.
     *
     * @param array $input    Initial array of values to pad.
     * @param int   $size     The new size of the array.
     * @param mixed $padValue Value to pad if $input is less than $size.
     * @param int   $padType  Optional argument to specify which end of the array to pad.
     *
     * @return array Returns a copy of the $input array padded to size specified by $size with value $padValue
     *
     * @throws InvalidArgumentException Thrown if $padType is invalid.
     */
    public static function pad(array $input, int $size, $padValue = null, int $padType = self::ARRAY_PAD_END) : array
    {
        if ($padType === self::ARRAY_PAD_END) {
            return array_pad($input, $size, $padValue);
        }

        if ($padType !== self::ARRAY_PAD_FRONT) {
            throw new InvalidArgumentException('Invalid $padType value provided');
        }

        while (count($input) < $size) {
            array_unshift($input, $padValue);
        }

        return $input;
    }

    /**
     * Removes duplicate values from an array.
     *
     * @param array $input     The array to be filtered.
     * @param int   $sortFlags Optional parameter used to modify the sorting behavior.
     * @param bool  $strict    If set to TRUE the filter will throw exception if the $input array contains duplicates.
     *
     * @return array
     *
     * @throws FilterException Thrown if the array contains duplicates and $strict is true.
     */
    public static function unique(
        array $input,
        int $sortFlags = self::ARRAY_UNIQUE_SORT_REGULAR,
        bool $strict = false
    ) : array {
        $unique = array_unique($input, $sortFlags);
        if ($unique !== $input && $strict === true) {
            $duplicateValues = self::findDuplicates($input);
            throw new DuplicateValuesException($duplicateValues);
        }

        return $unique;
    }

    private static function findDuplicates(array $input) : array
    {
        $temp = [];
        $duplicates = [];
        foreach ($input as $key => $value) {
            if (!in_array($value, $temp, true)) {
                $temp[] = $value;
                continue;
            }

            $duplicates[$key] = $value;
        }

        return $duplicates;
    }
}
