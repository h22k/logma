<?php

declare(strict_types=1);

namespace H22k\Logma\Tests\Unit\Source;

use DateTime;
use H22k\Logma\Formatter\Formatter;
use H22k\Logma\Log;
use H22k\Logma\LogLevel;
use H22k\Logma\Resource\StreamResource;
use H22k\Logma\Source\StreamSource;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
class StreamSourceTest extends TestCase
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function dataProvider(): array
    {
        return [
            'should not write to file since actual level is lower than min level' => [
                'minLevel' => LogLevel::CRITICAL,
                'actualLevel' => LogLevel::ERROR,
                'result' => false,
            ],
            'should write to file since actual level is higher than min level' => [
                'minLevel' => LogLevel::ALERT,
                'actualLevel' => LogLevel::EMERGENCY,
                'result' => true,
            ],
            'should write to file since both levels are equal' => [
                'minLevel' => LogLevel::ALERT,
                'actualLevel' => LogLevel::ALERT,
                'result' => true,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testShouldActRegardingToLogLevel(LogLevel $minLevel, LogLevel $actualLevel, bool $result): void
    {
        $formatter = $this->createMock(Formatter::class);
        $log = Log::createFrom('message', 'log', $actualLevel, new DateTime());
        $streamResource = $this->createMock(StreamResource::class);

        if ($result) {
            $streamResource->expects($this->once())
                ->method('write')
                ->willReturn(true)
            ;
        }

        $source = new StreamSource($streamResource, $formatter, $minLevel);

        $this->assertSame($result, $source->send($log));
    }
}
