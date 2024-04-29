<?php

declare(strict_types=1);

namespace H22k\Logma\Tests\Unit\Formatter;

use Generator;
use H22k\Logma\Formatter\JsonFormatter;
use H22k\Logma\Log;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class JsonFormatterTest extends FormatterTestCase
{
    protected function setUp(): void
    {
        $this->formatter = new JsonFormatter();
    }

    public static function customFormatDataProvider(): Generator
    {
        yield [
            'callback' => function (Log $log) {
                return json_encode(['message' => $log->getMessage()]);
            },
            'result' => sprintf('{"message":"%s"}', self::LOG_MESSAGE),
        ];

        yield [
            'callback' => function (Log $log) {
                return json_encode(['message' => $log->getMessage(), 'level' => $log->getLevelAsString()]);
            },
            'result' => sprintf('{"message":"%s","level":"%s"}', self::LOG_MESSAGE, self::LOG_LOGLEVEL->name()),
        ];
    }

    public function testFormat(): void
    {
        $logRecord = $this->formatter->formatMessage(self::$log);

        $expectedMessage = sprintf(
            '{
    "message": "%s",
    "time": {
        "date": "%s",
        "timezone_type": 3,
        "timezone": "%s"
    },
    "name": "%s",
    "level": "%s",
    "context": []
}',
            self::$log->getMessage(),
            self::$log->getLogTime()->format('Y-m-d H:i:s.u'),
            self::$log->getLogTime()->getTimezone()->getName(),
            self::$log->getName(),
            self::$log->getLevelAsString()
        );

        $this->assertSame($expectedMessage, $logRecord->getFormattedMessage());
    }
}
