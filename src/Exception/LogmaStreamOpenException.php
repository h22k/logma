<?php

declare(strict_types=1);

namespace H22k\Logma\Exception;

use Exception;

class LogmaStreamOpenException extends Exception
{
    public function __construct(string $fileName)
    {
        parent::__construct(
            sprintf('Stream [%s] couldn\' be opened!', $fileName)
        );
    }
}
