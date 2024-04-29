<?php

declare(strict_types=1);

namespace H22k\Logma\Resource;

use H22k\Logma\Exception\LogmaInvalidFileNameException;
use H22k\Logma\Exception\LogmaStreamOpenException;
use H22k\Logma\LogRecord;

class StreamResource
{
    private const string FILE_EXTENSION_KEY = 'extension';

    /**
     * @var resource
     */
    protected $stream;

    private readonly string $fileExtension;

    /**
     * @throws LogmaInvalidFileNameException
     * @throws LogmaStreamOpenException
     */
    public function __construct(
        private readonly string $streamPath,
    ) {
        $this->stream = $this->openStream();

        $pathInfo = $this->getPathInfo($this->streamPath);

        $this->fileExtension = $pathInfo[self::FILE_EXTENSION_KEY] ?? '';
    }

    public function __destruct()
    {
        if ($this->stream && is_resource($this->stream)) {
            fclose($this->stream);
            $this->stream = null;
        }
    }

    public function write(LogRecord $logRecord): bool
    {
        $isWritten = fwrite($this->stream, $logRecord->getFormattedMessage());

        return is_int($isWritten);
    }

    public function is(string $extension): bool
    {
        if ('' === $this->getFileExtension()) {
            return false;
        }

        return $this->getFileExtension() === $extension;
    }

    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }

    protected function isFileEmpty(): bool
    {
        clearstatcache();

        return 0 === filesize($this->streamPath);
    }

    /**
     * @return resource
     *
     * @throws LogmaStreamOpenException
     */
    private function openStream()
    {
        $stream = fopen($this->streamPath, 'a+');

        if (false === $stream) {
            throw new LogmaStreamOpenException($this->streamPath);
        }

        return $stream;
    }

    /**
     * @return array<string, string>
     *
     * @throws LogmaInvalidFileNameException
     */
    private function getPathInfo(string $streamPath): array
    {
        $pathInfo = pathinfo($streamPath);

        if (!is_array($pathInfo)) {
            throw new LogmaInvalidFileNameException($streamPath);
        }

        return $pathInfo;
    }
}
