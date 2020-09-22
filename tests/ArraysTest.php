<?php

namespace TraderInteractive\Filter;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TraderInteractive\Exceptions\FilterException;
use TraderInteractive\Filter\Exceptions\DuplicateValuesException;

/**
 * @coversDefaultClass \TraderInteractive\Filter\Arrays
 * @covers ::<private>
 */
final class ArraysTest extends TestCase
{
    /**
     * @test
     * @covers ::filter
     */
    public function filterBasicPass()
    {
        $this->assertSame(['boo'], Arrays::filter(['boo']));
    }

    /**
     * @test
     * @covers ::filter
     * @expectedException \TraderInteractive\Exceptions\FilterException
     * @expectedExceptionMessage Value '1' is not an array
     */
    public function filterFailNotArray()
    {
        Arrays::filter(1);
    }

    /**
     * @test
     * @covers ::filter
     * @expectedException \TraderInteractive\Exceptions\FilterException
     * @expectedExceptionMessage $value count of 0 is less than 1
     */
    public function filterFailEmpty()
    {
        Arrays::filter([]);
    }

    /**
     * @test
     * @covers ::filter
     * @expectedException \TraderInteractive\Exceptions\FilterException
     * @expectedExceptionMessage $value count of 1 is less than 2
     */
    public function filterCountLessThanMin()
    {
        Arrays::filter([0], 2);
    }

    /**
     * @test
     * @covers ::filter
     * @expectedException \TraderInteractive\Exceptions\FilterException
     * @expectedExceptionMessage $value count of 2 is greater than 1
     */
    public function filterCountGreaterThanMax()
    {
        Arrays::filter([0, 1], 1, 1);
    }

    /**
     * @test
     * @covers ::in
     */
    public function inPassStrict()
    {
        $this->assertSame('boo', Arrays::in('boo', ['boo']));
    }

    /**
     * @test
     * @covers ::in
     */
    public function inFailStrict()
    {
        try {
            Arrays::in('0', [0]);
            $this->fail();
        } catch (FilterException $e) {
            $this->assertSame("Value '0' is not in array array (\n  0 => 0,\n)", $e->getMessage());
        }
    }

    /**
     * @test
     * @covers ::in
     */
    public function inFailNotStrict()
    {
        try {
            Arrays::in('boo', ['foo'], false);
            $this->fail();
        } catch (FilterException $e) {
            $this->assertSame("Value 'boo' is not in array array (\n  0 => 'foo',\n)", $e->getMessage());
        }
    }

    /**
     * @test
     * @covers ::in
     */
    public function inPassNotStrict()
    {
        $this->assertSame('0', Arrays::in('0', [0], false));
    }

    /**
     * Verifies the basic behavior of the flatten filter.
     *
     * @test
     * @covers ::flatten
     */
    public function flatten()
    {
        $this->assertSame([1, 2, 3, 4, 5], Arrays::flatten([[1, 2], [[3, [4, 5]]]]));
    }

    /**
     * @test
     * @covers ::arrayize
     */
    public function arrayizeReturnsInputIfItIsAnArray()
    {
        $this->assertSame([1, 2, 3, 4, 5], Arrays::arrayize([1, 2, 3, 4, 5]));
    }

    /**
     * @test
     * @covers ::arrayize
     */
    public function arrayizeWrapsNonArrayValue()
    {
        $value = new \StdClass();
        $this->assertSame([$value], Arrays::arrayize($value));
    }

    /**
     * @test
     * @covers ::arrayize
     */
    public function arrayizeConvertsNullToEmptyArray()
    {
        $this->assertSame([], Arrays::arrayize(null));
    }

    /**
     * @test
     * @covers ::copy
     */
    public function copy()
    {
        $source = ['foo' => 1, 'bar' => 2, 'extra' => 3];
        $keyMap = [
            'far' => 'foo',
            'bar',
        ];
        $result = Arrays::copy($source, $keyMap);
        $this->assertSame(
            [
                'far' => $source['foo'],
                'bar' => $source['bar'],
            ],
            $result
        );
    }

    /**
     * @test
     * @covers ::copyEach
     */
    public function copyEach()
    {
        $input = [
            ['foo' => 1, 'bar' => 2],
            ['foo' => 3, 'bar' => 4],
        ];
        $keyMap = [
            'far' => 'foo',
            'bar',
        ];
        $result = Arrays::copyEach($input, $keyMap);
        $this->assertSame(
            [
                [
                    'far' => 1,
                    'bar' => 2,
                ],
                [
                    'far' => 3,
                    'bar' => 4,
                ],
            ],
            $result
        );
    }

    /**
     * @test
     * @covers ::pad
     */
    public function pad()
    {
        $result = Arrays::pad([12, 10, 9], 5, 0);
        $this->assertSame([12, 10, 9, 0, 0], $result);
    }

    /**
     * @test
     * @covers ::pad
     */
    public function padArrayLengthGreaterThanSize()
    {
        $result = Arrays::pad(['a', 'b', 'c'], 2, 0);
        $this->assertSame(['a', 'b', 'c'], $result);
    }

    /**
     * @test
     * @covers ::pad
     */
    public function padFront()
    {
        $result = Arrays::pad(['a', 'b', 'c'], 5, null, Arrays::ARRAY_PAD_FRONT);
        $this->assertSame([null, null, 'a', 'b', 'c'], $result);
    }

    /**
     * @test
     * @covers ::pad
     */
    public function padInvalidPadType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid $padType value provided');
        Arrays::pad(['a', 'b', 'c'], 5, null, 0);
    }

    /**
     * @test
     * @covers ::unique
     */
    public function unique()
    {
        $input = ['foo', 'bar', 'foo'];
        $filteredValue = Arrays::unique($input);
        $this->assertSame(['foo', 'bar'], $filteredValue);
    }

    /**
     * @test
     * @covers ::unique
     */
    public function uniqueStrict()
    {
        $input = ['foo', 'bar', 'foo'];
        $expectedDuplicates = ['2' => 'foo'];
        $expectedException = new DuplicateValuesException($expectedDuplicates);
        $this->expectException(DuplicateValuesException::class);
        $this->expectExceptionMessage($expectedException->getMessage());
        Arrays::unique($input, Arrays::ARRAY_UNIQUE_SORT_STRING, true);
    }
}
