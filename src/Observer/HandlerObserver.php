<?php

declare(strict_types=1);

namespace H22k\Logma\Observer;

use Exception;
use H22k\Logma\Log;

interface HandlerObserver
{
    public function update(Log $log, Exception $exception): void;
}
