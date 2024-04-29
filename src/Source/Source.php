<?php

declare(strict_types=1);

namespace H22k\Logma\Source;

use H22k\Logma\Formatter\Formatter;
use H22k\Logma\Log;
use H22k\Logma\LogLevel;
use H22k\Logma\LogRecord;

abstract class Source
{
    public function __construct(
        protected Formatter $formatter,
        private readonly LogLevel $sourceMinimumLogLevel,
    ) {}

    public function send(Log $log): bool
    {
        if (!$this->isLogWriteable($log)) {
            return false;
        }

        return $this->write($this->formatter->formatMessage($log));
    }

    abstract protected function write(LogRecord $logRecord): bool;

    private function isLogWriteable(Log $log): bool
    {
        return $log->getLevel()->value <= $this->sourceMinimumLogLevel->value;
    }
}
