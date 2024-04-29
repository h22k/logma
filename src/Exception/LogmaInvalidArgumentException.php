<?php

declare(strict_types=1);

namespace H22k\Logma\Exception;

use InvalidArgumentException;

class LogmaInvalidArgumentException extends InvalidArgumentException
{
    public function __construct(string $expectedType, mixed $givenData)
    {
        $givenType = $this->getGivenType($givenData);

        parent::__construct(sprintf(
            'You should have passed %s type, but you have passed %s!',
            $expectedType,
            $givenType,
        ));
    }

    private function getGivenType(mixed $givenData): string
    {
        $givenType = gettype($givenData);

        if ('object' === $givenType && get_class($givenData)) {
            return get_class($givenData);
        }

        return $givenType;
    }
}
