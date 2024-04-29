<?php

namespace H22k\Logma\Observer;

use Exception;
use H22k\Logma\Log;
use LogicException;

trait ExceptionNotifier
{
    /**
     * @var array<string, HandlerObserver>
     */
    protected array $observers = [];

    public function attach(string $key, HandlerObserver $observer): self
    {
        if (array_key_exists($key, $this->observers)) {
            throw new LogicException('Already attached');
        }
        $this->observers[$key] = $observer;

        return $this;
    }

    public function detach(string $key): self
    {
        if (array_key_exists($key, $this->observers)) {
            unset($this->observers[$key]);
        }

        return $this;
    }

    public function notify(Log $log, Exception $exception): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($log, $exception);
        }
    }
}
