<?php

declare(strict_types=1);

namespace H22k\Logma\Formatter;

use H22k\Logma\Exception\LogmaInvalidJsonException;
use H22k\Logma\Log;

class JsonFormatter extends Formatter
{
    /**
     * @throws LogmaInvalidJsonException
     */
    protected function getFormattedMessage(Log $log): string
    {
        $jsonString = json_encode(
            [
                'message' => $log->getMessage(),
                'time' => $log->getLogTime(),
                'name' => $log->getName(),
                'level' => $log->getLevelAsString(),
                'context' => $log->getContext(),
            ],
            JSON_PRETTY_PRINT
        );

        if (false === $jsonString || !json_validate($jsonString)) {
            throw new LogmaInvalidJsonException(json_last_error_msg(), $log);
        }

        return $jsonString;
    }
}
