<?php

declare(strict_types=1);

namespace H22k\Logma;

readonly class LogRecord
{
    public function __construct(
        private string $formattedMessage,
    ) {}

    public function getFormattedMessage(): string
    {
        return $this->formattedMessage;
    }
}
