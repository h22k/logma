<?php

declare(strict_types=1);

namespace H22k\Logma\Exception;

use Exception;

class LogmaInvalidFileNameException extends Exception
{
    public function __construct(string $fileName)
    {
        parent::__construct(
            sprintf('[%s] is not a valid filename!', $fileName)
        );
    }
}
