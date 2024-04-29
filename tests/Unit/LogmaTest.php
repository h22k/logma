<?php

declare(strict_types=1);

namespace H22k\Logma\Tests\Unit;

use DateTime;
use Exception;
use Generator;
use H22k\Logma\Exception\LogmaInvalidArgumentException;
use H22k\Logma\Exception\LogmaInvalidJsonException;
use H22k\Logma\Log;
use H22k\Logma\LogLevel;
use H22k\Logma\Logma;
use H22k\Logma\Observer\HandlerObserver;
use H22k\Logma\Source\Source;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversNothing]
class LogmaTest extends TestCase
{
    private Logma $logma;

    protected function setUp(): void
    {
        $this->logma = new Logma('test');
    }

    public static function properSourceReturnDataProvider(): Generator
    {
        yield [true];

        yield [false];
    }

    /**
     * @throws Exception
     */
    public function testAcceptJustEnumParameter(): void
    {
        $this->expectException(LogmaInvalidArgumentException::class);

        $this->logma->log('info', 'log');
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[DataProvider('properSourceReturnDataProvider')]
    public function testDoNotNotifyIfThereIsNotAnyError(bool $returnData): void
    {
        $source = $this->createMock(Source::class);

        $source->expects($this->once())
            ->method('send')
            ->willReturn($returnData)
        ;

        $observer = $this->createMock(HandlerObserver::class);
        // running this function means that an error was thrown.
        $observer->expects($this->exactly(0))
            ->method('update')
            ->willReturnCallback(function () {
                $this->fail();
            })
        ;

        $this->logma->addSource($source);
        $this->logma->attach('observer', $observer);

        $this->logma->info('log');
        $this->assertTrue(true);
    }

    public function testNotifyIfThereIsAnError(): void
    {
        $source = $this->createMock(Source::class);
        $log = Log::createFrom('message', 'log', LogLevel::INFO, new DateTime());

        $source->expects($this->once())
            ->method('send')
            ->willThrowException(new LogmaInvalidJsonException('invalid json', $log))
        ;

        $observer = $this->createMock(HandlerObserver::class);
        // running this function means that an error was thrown.
        $observer->expects($this->once())
            ->method('update')
            ->willReturnCallback(function () {
                $this->assertTrue(true);
            })
        ;

        $this->logma->addSource($source);
        $this->logma->attach('observer', $observer);

        $this->logma->info('log');
    }
}
