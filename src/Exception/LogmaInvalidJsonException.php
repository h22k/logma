<?php

declare(strict_types=1);

namespace H22k\Logma\Exception;

use Exception;
use H22k\Logma\Log;

class LogmaInvalidJsonException extends Exception
{
    public function __construct(string $error, Log $log)
    {
        parent::__construct(sprintf('Error happened when json transforming: %s [%s]', $error, serialize($log)));
    }
}
