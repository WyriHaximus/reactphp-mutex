<?php

declare(strict_types=1);

namespace WyriHaximus\React\Mutex;

use React\Promise\PromiseInterface;

interface MutexInterface
{
    /**
     * Acquire a mutex. Will resolve with either a Lock object or false when it can't acquire the lock because another
     * requester already acquired it.
     */
    public function acquire(string $key, float $ttl): PromiseInterface;

    /**
     * Release a previously acquired lock.
     */
    public function release(Lock $lock): PromiseInterface;
}
