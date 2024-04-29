<?php

declare(strict_types=1);

namespace H22k\Logma\Exception;

use Exception;

class LogmaStreamIsNotWriteableException extends Exception
{
    public function __construct(string $stream)
    {
        parent::__construct(sprintf('%s is not writable!', $stream));
    }
}
