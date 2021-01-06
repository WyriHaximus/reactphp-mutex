<?php

declare(strict_types=1);

namespace WyriHaximus\React\Mutex;

use React\Promise\PromiseInterface;

use function array_key_exists;
use function bin2hex;
use function random_bytes;
use function React\Promise\resolve;

use const WyriHaximus\Constants\Boolean\FALSE_;
use const WyriHaximus\Constants\Boolean\TRUE_;

final class Memory implements MutexInterface
{
    private const RANDOM_BYTES_LENGTH = 13;
    /** @var array<Lock> */
    private array $locks = [];

    public function acquire(string $key): PromiseInterface
    {
        if (array_key_exists($key, $this->locks)) {
            return resolve(null);
        }

        $rng               = bin2hex(random_bytes(self::RANDOM_BYTES_LENGTH));
        $this->locks[$key] = new Lock($key, $rng);

        return resolve($this->locks[$key]);
    }

    public function release(Lock $lock): PromiseInterface
    {
        if (! array_key_exists($lock->getKey(), $this->locks)) {
            return resolve(FALSE_);
        }

        return resolve(TRUE_);
    }
}
