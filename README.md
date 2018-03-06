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

[![Documentation](https://img.shields.io/badge/reference-phpdoc-blue.svg?style=flat)](https://traderinteractive.github.io/filter-arrays-php/)

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

## Project Build

With a checkout of the code get [Composer](http://getcomposer.org) in your PATH and run:
``sh
composer install
./vendor/bin/phpunit
./vendor/bin/phpcs
```
For more information on our build process, read through out our [Contribution Guidelines](./.github/CONTRIBUTING.md).
