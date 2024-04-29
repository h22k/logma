<?php

declare(strict_types=1);

namespace H22k\Logma\Source;

use H22k\Logma\Formatter\Formatter;
use H22k\Logma\LogLevel;
use H22k\Logma\LogRecord;
use H22k\Logma\Resource\StreamResource;

class StreamSource extends Source
{
    public function __construct(
        private readonly StreamResource $streamResource,
        Formatter $formatter,
        LogLevel $sourceMinimumLogLevel = LogLevel::DEBUG,
    ) {
        parent::__construct($formatter, $sourceMinimumLogLevel);
    }

    protected function write(LogRecord $logRecord): bool
    {
        return $this->streamResource->write($logRecord);
    }
}
