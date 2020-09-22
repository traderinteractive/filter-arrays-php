<?php

namespace Exceptions;

use PHPUnit\Framework\TestCase;
use TraderInteractive\Filter\Exceptions\DuplicateValuesException;

/**
 * @coversDefaultClass \TraderInteractive\Filter\Exceptions\DuplicateValuesException
 */
final class DuplicateValuesExceptionTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function basicUsage()
    {
        $duplicates = ['foo', 'bar'];
        $exception = new DuplicateValuesException($duplicates);
        $this->assertSame(
            sprintf(DuplicateValuesException::ERROR_FORMAT, var_export($duplicates, true)),
            $exception->getMessage()
        );
    }
}
