<?php

declare(strict_types=1);

namespace H22k\Logma\Tests\Unit\Formatter;

use Generator;
use H22k\Logma\Formatter\PlainTextFormatter;
use H22k\Logma\Log;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class PlainTextFormatterTest extends FormatterTestCase
{
    protected function setUp(): void
    {
        $this->formatter = new PlainTextFormatter();
    }

    public static function customFormatDataProvider(): Generator
    {
        yield ['callback' => function (Log $log) {
            return $log->getName();
        }, 'result' => self::LOG_NAME];

        yield ['callback' => function (Log $log) {
            return sprintf('%s | %s', $log->getMessage(), $log->getLevelAsString());
        }, 'result' => sprintf('%s | %s', self::LOG_MESSAGE, self::LOG_LOGLEVEL->name())];
    }

    public function testFormat(): void
    {
        $logRecord = $this->formatter->formatMessage(self::$log);
        $expectedMessage = sprintf(
            '[%s] %s.%s | %s %s %s',
            self::$log->getLogTime()->format('d/m/y H:i:s'),
            self::$log->getName(),
            self::$log->getLevelAsString(),
            self::$log->getMessage(),
            serialize(self::$log->getContext()),
            "\n",
        );

        $this->assertSame($expectedMessage, $logRecord->getFormattedMessage());
    }
}
