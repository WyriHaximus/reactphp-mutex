<?php

declare(strict_types=1);

namespace WyriHaximus\React\Mutex;

use React\Cache\ArrayCache;
use React\Cache\CacheInterface;
use React\Promise\PromiseInterface;
use WyriHaximus\React\Mutex\Contracts\LockInterface;
use WyriHaximus\React\Mutex\Contracts\MutexInterface;

use function bin2hex;
use function random_bytes;
use function React\Promise\resolve;
use function WyriHaximus\React\timedPromise;

final class Memory implements MutexInterface
{
    private const NO_MORE_ATTEMPTS_LEFT = 0;
    private const RANDOM_BYTES_LENGTH   = 13;
    private CacheInterface $locks;

    public function __construct()
    {
        $this->locks = new ArrayCache();
    }

    public function acquire(string $key, float $ttl): PromiseInterface
    {
        /** @phpstan-ignore-next-line */
        return $this->locks->has($key)->then(function (bool $has) use ($key, $ttl): Lock|null {
            if ($has) {
                return null;
            }

            $rng  = bin2hex(random_bytes(self::RANDOM_BYTES_LENGTH));
            $lock = new Lock($key, $rng);
            $this->locks->set($key, $lock, $ttl);

            return $lock;
        });
    }

    public function spin(string $key, float $ttl, int $attempts, float $interval): PromiseInterface
    {
        /** @phpstan-ignore-next-line */
        return $this->acquire($key, $ttl)->then(function (LockInterface|null $lock) use ($key, $ttl, $attempts, $interval): PromiseInterface {
            if ($lock instanceof LockInterface || $attempts === self::NO_MORE_ATTEMPTS_LEFT) {
                return resolve($lock);
            }

            return timedPromise($interval, [$key, $ttl, --$attempts, $interval])->then(function (array $args): PromiseInterface {
                return $this->spin(...$args);
            });
        });
    }

    public function release(LockInterface $lock): PromiseInterface
    {
        return $this->locks->has($lock->key())->then(function (bool $has) use ($lock): PromiseInterface|bool {
            return $has ? $this->locks->get($lock->key()) : $has;
        })->then(
            /** @phpstan-ignore-next-line */
            function (Lock|null $storedLock) use ($lock): PromiseInterface|bool {
                if ($storedLock instanceof Lock && $storedLock->rng() === $lock->rng()) {
                    return $this->locks->delete($lock->key());
                }

                return ! ($storedLock instanceof Lock) || $storedLock->rng() === $lock->rng();
            },
        );
    }
}
