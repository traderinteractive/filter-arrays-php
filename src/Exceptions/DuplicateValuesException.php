<?php

namespace TraderInteractive\Filter\Exceptions;

use TraderInteractive\Exceptions\FilterException;

class DuplicateValuesException extends FilterException
{
    /**
     * @var string
     */
    const ERROR_FORMAT = "Array contains the following duplicate values: %s";

    /**
     * @param array $duplicateValues The duplicate values found in the array.
     */
    public function __construct(array $duplicateValues)
    {
        $message = sprintf(self::ERROR_FORMAT, var_export($duplicateValues, true));
        parent::__construct($message);
    }
}
