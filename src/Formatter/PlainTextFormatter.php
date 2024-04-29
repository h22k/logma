<?php

declare(strict_types=1);

namespace H22k\Logma\Formatter;

use H22k\Logma\Log;

class PlainTextFormatter extends Formatter
{
    protected function getFormattedMessage(Log $log): string
    {
        return sprintf(
            '[%s] %s.%s | %s %s %s',
            $log->getLogTime()->format('d/m/y H:i:s'),
            $log->getName(),
            $log->getLevelAsString(),
            $log->getMessage(),
            serialize($log->getContext()),
            "\n",
        );
    }
}
