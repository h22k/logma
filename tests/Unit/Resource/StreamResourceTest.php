<?php

declare(strict_types=1);

namespace H22k\Logma\Tests\Unit\Resource;

use bovigo\vfs\vfsDirectory;
use bovigo\vfs\vfsStream;
use Generator;
use H22k\Logma\Exception\LogmaInvalidFileNameException;
use H22k\Logma\Exception\LogmaStreamIsNotWriteableException;
use H22k\Logma\Exception\LogmaStreamOpenException;
use H22k\Logma\LogRecord;
use H22k\Logma\Resource\StreamResource;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
class StreamResourceTest extends TestCase
{
    private const string CREATED_FILE_NAME_PATH = __DIR__.'/log.log';
    private vfsDirectory $fileDirectory;

    protected function setUp(): void
    {
        $this->fileDirectory = vfsStream::setup();
    }

    protected function tearDown(): void
    {
        if (file_exists(self::CREATED_FILE_NAME_PATH)) {
            unlink(self::CREATED_FILE_NAME_PATH);
        }
    }

    public static function writeDataProvider(): Generator
    {
        yield ['logging'];

        yield ['{
    "message": "first log",
    "time": {
        "date": "2024-04-05 20:32:19.678759",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "name": "logmaTest",
    "level": "INFO",
    "context": {
        "context": "cond"
    }
}'];

        yield [''];

        yield ["\n"];
    }

    public static function fileNameDataProvider(): Generator
    {
        yield ['fileName' => 'test.json', 'extension' => 'json'];

        yield ['fileName' => 'test.JSON', 'extension' => 'JSON'];

        yield ['fileName' => 'test.txt', 'extension' => 'txt'];

        yield ['fileName' => 'test.log', 'extension' => 'log'];

        yield ['fileName' => 'test', 'extension' => ''];
    }

    /**
     * @throws LogmaStreamOpenException
     * @throws LogmaInvalidFileNameException
     */
    public function testNonWritableFile(): void
    {
        $nonWritableFile = vfsStream::newFile('nonWritableFile.log', 0o00)->at($this->fileDirectory);
        $filePath = $nonWritableFile->url();

        $this->expectException(LogmaStreamIsNotWriteableException::class);
        $this->expectExceptionMessage(sprintf('%s is not writable!', $filePath));

        new StreamResource($filePath);
    }

    public function testBadFile(): void
    {
        @fopen(self::CREATED_FILE_NAME_PATH, 'a+');
        chmod(self::CREATED_FILE_NAME_PATH, 222);

        $this->expectException(LogmaStreamOpenException::class);

        new StreamResource(self::CREATED_FILE_NAME_PATH);
        $this->assertTrue(true);
    }

    public function testProperFile(): void
    {
        $file = vfsStream::newFile('proper.log', 755)->at($this->fileDirectory);
        $filePath = $file->url();

        new StreamResource($filePath);

        $this->assertTrue(true);
    }

    #[DataProvider('writeDataProvider')]
    public function testWrite(string $content): void
    {
        $file = vfsStream::newFile('proper.log', 755)->at($this->fileDirectory);
        $filePath = $file->url();

        $stream = new StreamResource($filePath);

        $logRecord = $this->createMock(LogRecord::class);
        $logRecord->expects($this->once())
            ->method('getFormattedMessage')
            ->willReturn($content)
        ;

        $stream->write($logRecord);

        $this->assertSame($content, $file->getContent());
    }

    #[DataProvider('fileNameDataProvider')]
    public function testShouldRecognizeFileExtensions(string $fileName, string $extension): void
    {
        $file = vfsStream::newFile($fileName, 755)->at($this->fileDirectory);
        $filePath = $file->url();

        $stream = new StreamResource($filePath);

        $this->assertTrue($stream->is($extension));
    }
}
