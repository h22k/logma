<?php

declare(strict_types=1);

namespace H22k\Logma;

/**
 * @see https://www.rfc-editor.org/rfc/rfc5424.html
 */
enum LogLevel: int
{
    case DEBUG = 7;

    case INFO = 6;

    case NOTICE = 5;

    case WARNING = 4;

    case ERROR = 3;

    case CRITICAL = 2;

    case ALERT = 1;

    case EMERGENCY = 0;

    public function name(): string
    {
        return match ($this) {
            LogLevel::DEBUG => 'DEBUG',
            LogLevel::INFO => 'INFO',
            LogLevel::NOTICE => 'NOTICE',
            LogLevel::WARNING => 'WARNING',
            LogLevel::ERROR => 'ERROR',
            LogLevel::CRITICAL => 'CRITICAL',
            LogLevel::ALERT => 'ALERT',
            LogLevel::EMERGENCY => 'EMERGENCY',
        };
    }
}
