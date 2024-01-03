# TraderInteractive Filter\Arrays

[![Build Status](https://travis-ci.org/traderinteractive/filter-arrays-php.svg?branch=master)](https://travis-ci.org/traderinteractive/filter-arrays-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/traderinteractive/filter-arrays-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/traderinteractive/filter-arrays-php/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/traderinteractive/filter-arrays-php/badge.svg?branch=master)](https://coveralls.io/github/traderinteractive/filter-arrays-php?branch=master)

[![Latest Stable Version](https://poser.pugx.org/traderinteractive/filter-arrays/v/stable)](https://packagist.org/packages/traderinteractive/filter-arrays)
[![Latest Unstable Version](https://poser.pugx.org/traderinteractive/filter-arrays/v/unstable)](https://packagist.org/packages/traderinteractive/filter-arrays)
[![License](https://poser.pugx.org/traderinteractive/filter-arrays/license)](https://packagist.org/packages/traderinteractive/filter-arrays)

[![Total Downloads](https://poser.pugx.org/traderinteractive/filter-arrays/downloads)](https://packagist.org/packages/traderinteractive/filter-arrays)
[![Daily Downloads](https://poser.pugx.org/traderinteractive/filter-arrays/d/daily)](https://packagist.org/packages/traderinteractive/filter-arrays)
[![Monthly Downloads](https://poser.pugx.org/traderinteractive/filter-arrays/d/monthly)](https://packagist.org/packages/traderinteractive/filter-arrays)

A filtering implementation for verifying correct data and performing typical modifications to arrays

## Requirements

Requires PHP 7.0 or newer and uses composer to install further PHP dependencies.  See the [composer specification](composer.json) for more details.

## Installation

filter-arrays-php can be installed for use in your project using [composer](http://getcomposer.org).
The recommended way of using this library in your project is to add a `composer.json` file to your project.  The following contents would add filter-arrays-php as a dependency:
```sh
composer require traderinteractive/filter-arrays
```

## Included Filters

#### Arrays::copy
This filter will copy values from the input array into the resulting array using the destination key map.
```php
$input = ['foo' => 1, 'bar' => 2];
$keyMap = ['FOO_VALUE' => 'foo', 'BAR_VALUE' => 'bar'];
$result = \TraderInteractive\Filter\Arrays::copy($input, $keyMap);
assert($result === ['FOO_VALUE' => 1, 'BAR_VALUE' => 2]);
```

#### Arrays::copyEach
This filter will copy values from each array within the input array into the resulting array using the destination key map.
```php
$input = [
    ['foo' => 1, 'bar' => 2],
    ['foo' => 3, 'bar' => 4],
];
$keyMap = ['FOO_VALUE' => 'foo', 'BAR_VALUE' => 'bar'];
$result = \TraderInteractive\Filter\Arrays::copyEach($input, $keyMap);
assert($result === [['FOO_VALUE' => 1, 'BAR_VALUE' => 2], ['FOO_VALUE' => 3, 'BAR_VALUE' => 4]]);
```
#### Arrays::in
This filter is a wrapper around `in_array` including support for strict equality testing.

The following does a strict check for `$value` against the 3 accepted values.
```php
\TraderInteractive\Filter\Arrays::in($value, ['a', 'b', 'c']);
```

#### Arrays::filter

This filter verifies that the argument is an array and checks the length of the array against bounds.  The
default bounds are 1+, so an empty array fails by default.

The following checks that the `$value` is an array with exactly 3 elements.
```php
\TraderInteractive\Filter\Arrays::filter($value, 3, 3);
```

#### Arrays::flatten

This filter flattens a multi-dimensional array to a single dimension.  The order of values will be
maintained, but the keys themselves will not.  For example:
```php
$value = \TraderInteractive\Filter\Arrays::flatten([[1, 2], [3, [4, 5]]]);
assert($value === [1, 2, 3, 4, 5]);
```

#### Arrays::implode

This filter is a wrapper to the PHP `implode` function. It joins an array of strings with the optional glue string. 
```php
$value = \TraderInteractive\Filter\Arrays::implode(['lastname', 'email', 'phone'], ',');
assert($value === 'lastname,email,phone');
```

#### Arrays::pad

This filter pads an array to the specified length with a value. Padding optionally to the front or end of the array.

```php
$value = \TraderInteractive\Filter\Arrays::pad([1, 2], 5, 0, \TraderInteractive\Filter\Arrays::ARRAY_PAD_FRONT);
assert($value === [0, 0, 0, 1, 2]);
```

#### Arrays::unique

This filter removes any duplicate values in the given array. Optionally throwing an exception if duplicate values are found.

```php
$value = \TraderInteractive\Filter\Arrays::unique(['foo', 'bar', 'foo']);
assert($value === ['foo', 'bar']);
```

## Project Build

With a checkout of the code get [Composer](http://getcomposer.org) in your PATH and run:
``sh
composer install
./vendor/bin/phpunit
./vendor/bin/phpcs
```
For more information on our build process, read through out our [Contribution Guidelines](./.github/CONTRIBUTING.md).
