<?php

declare(strict_types=1);

namespace H22k\Logma\Tests\Unit\Formatter;

use Closure;
use DateTime;
use Generator;
use H22k\Logma\Formatter\Formatter;
use H22k\Logma\Log;
use H22k\Logma\LogLevel;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
abstract class FormatterTestCase extends TestCase
{
    protected const string LOG_MESSAGE = 'message';
    protected const string LOG_NAME = 'log';
    protected const LogLevel LOG_LOGLEVEL = LogLevel::DEBUG;
    protected static Log $log;
    protected Formatter $formatter;

    public static function setUpBeforeClass(): void
    {
        self::$log = Log::createFrom(self::LOG_MESSAGE, self::LOG_NAME, self::LOG_LOGLEVEL, new DateTime());
    }

    abstract public static function customFormatDataProvider(): Generator;

    #[DataProvider('customFormatDataProvider')]
    public function testCustomFormat(Closure $callback, string $result): void
    {
        $this->formatter->setCustomFormat($callback);

        $logRecord = $this->formatter->formatMessage(self::$log);

        $this->assertSame($result, $logRecord->getFormattedMessage());
    }
}
