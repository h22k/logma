<?php

declare(strict_types=1);

namespace H22k\Logma;

use DateTimeInterface;

readonly class Log
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        private string $message,
        private string $name,
        private LogLevel $level,
        private DateTimeInterface $logTime,
        private array $context = [],
    ) {}

    /**
     * @param array<string, mixed> $context
     */
    public static function createFrom(
        string $message,
        string $name,
        LogLevel $level,
        DateTimeInterface $logTime,
        array $context = [],
    ): self {
        return new self($message, $name, $level, $logTime, $context);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getLevel(): LogLevel
    {
        return $this->level;
    }

    public function getLevelAsString(): string
    {
        return $this->level->name();
    }

    public function getLogTime(): DateTimeInterface
    {
        return $this->logTime;
    }

    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
