<?php

declare(strict_types=1);

namespace H22k\Logma;

use DateTime;
use Exception;
use H22k\Logma\Exception\LogmaInvalidArgumentException;
use H22k\Logma\Formatter\PlainTextFormatter;
use H22k\Logma\Observer\ExceptionNotifier;
use H22k\Logma\Resource\StreamResource;
use H22k\Logma\Source\Source;
use H22k\Logma\Source\StreamSource;
use Psr\Log\AbstractLogger;
use Stringable;

class Logma extends AbstractLogger
{
    use ExceptionNotifier;

    /**
     * @var array<Source>
     */
    private array $sources = [];

    private Source $devSource;

    public function __construct(
        private readonly string $name,
        private bool $isDevelopment = false,
    ) {
        if ($this->isDevelopment) {
            $this->devSource = new StreamSource(
                new StreamResource('php://stdout'),
                new PlainTextFormatter()
            );
        }
    }

    public function addSource(Source $source): self
    {
        $this->sources[] = $source;

        return $this;
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws Exception
     */
    public function info(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * @param LogLevel             $level
     * @param array<string, mixed> $context
     *
     * @throws Exception|LogmaInvalidArgumentException
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        if (!$level instanceof LogLevel) {
            throw new LogmaInvalidArgumentException(
                LogLevel::class,
                $level
            );
        }

        foreach ($this->sources as $source) {
            $log = Log::createFrom(
                (string) $message,
                $this->name,
                $level,
                new DateTime(),
                $context,
            );

            try {
                $isLogSent = $source->send($log);

                if ($isLogSent) {
                    $this->debugConsole('Added log for %s source! [%s]', get_class($source), serialize($log));
                }
            } catch (Exception $exception) {
                $this->notify($log, $exception);
            }
        }
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws Exception
     */
    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws Exception
     */
    public function notice(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws Exception
     */
    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws Exception
     */
    public function error(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws Exception
     */
    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws Exception
     */
    public function alert(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws Exception
     */
    public function emergency(string|Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    private function debugConsole(string $messageFormat, mixed ...$params): void
    {
        if ($this->isDevelopment && isset($this->devSource)) {
            $message = sprintf($messageFormat, ...$params);
            $this->devSource->send(Log::createFrom(
                $message,
                $this->name,
                LogLevel::DEBUG,
                new DateTime(),
            ));
        }
    }
}
