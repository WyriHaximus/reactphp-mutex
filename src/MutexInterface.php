<?php declare(strict_types=1);

namespace WyriHaximus\React\Mutex;

use React\Promise\PromiseInterface;

interface MutexInterface
{
    /**
     * Acquire a mutex. Will resolve with either a Lock object or false when it can't acquire the lock because another
     * requester already acquired it.
     *
     * @param string $key
     *
     * @return PromiseInterface<Lock>
     */
    public function acquire(string $key): PromiseInterface;

    /**
     * Release a previously acquired lock.
     *
     * @param  Lock             $lock
     * @return PromiseInterface
     */
    public function release(Lock $lock): PromiseInterface;
}
