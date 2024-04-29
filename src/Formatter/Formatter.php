<?php

declare(strict_types=1);

namespace H22k\Logma\Formatter;

use Closure;
use H22k\Logma\Log;
use H22k\Logma\LogRecord;

abstract class Formatter
{
    public function __construct(protected ?Closure $callback = null) {}

    public function formatMessage(Log $log): LogRecord
    {
        if (null !== $this->callback) {
            $formattedMessage = call_user_func($this->callback, $log);
        } else {
            $formattedMessage = $this->getFormattedMessage($log);
        }

        return new LogRecord($formattedMessage);
    }

    public function setCustomFormat(Closure $callback): void
    {
        $this->callback = $callback;
    }

    abstract protected function getFormattedMessage(Log $log): string;
}
