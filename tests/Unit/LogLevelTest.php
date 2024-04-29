<?php

declare(strict_types=1);

namespace H22k\Logma\Tests\Unit;

use H22k\Logma\LogLevel;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
class LogLevelTest extends TestCase
{
    public function testNameMethodShouldReturnCorrectName(): void
    {
        $this->assertSame('ALERT', LogLevel::ALERT->name());
        $this->assertSame('CRITICAL', LogLevel::CRITICAL->name());
        $this->assertSame('ERROR', LogLevel::ERROR->name());
        $this->assertSame('WARNING', LogLevel::WARNING->name());
        $this->assertSame('NOTICE', LogLevel::NOTICE->name());
        $this->assertSame('INFO', LogLevel::INFO->name());
        $this->assertSame('DEBUG', LogLevel::DEBUG->name());
    }
}
