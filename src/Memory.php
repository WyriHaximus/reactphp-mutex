<?php

declare(strict_types=1);

namespace WyriHaximus\React\Mutex;

use React\Cache\ArrayCache;
use React\Cache\CacheInterface;
use React\Promise\PromiseInterface;

use function bin2hex;
use function random_bytes;

use const WyriHaximus\Constants\Boolean\FALSE_;

final class Memory implements MutexInterface
{
    private const RANDOM_BYTES_LENGTH = 13;
    private CacheInterface $locks;

    public function __construct()
    {
        $this->locks = new ArrayCache();
    }

    public function acquire(string $key, float $ttl): PromiseInterface
    {
        /**
         * @phpstan-ignore-next-line
         * @psalm-suppress TooManyTemplateParams
         */
        return $this->locks->has($key)->then(function (bool $has) use ($key, $ttl): ?Lock {
            if ($has) {
                return null;
            }

            $rng  = bin2hex(random_bytes(self::RANDOM_BYTES_LENGTH));
            $lock = new Lock($key, $rng);
            $this->locks->set($key, $lock, $ttl);

            return $lock;
        });
    }

    public function release(Lock $lock): PromiseInterface
    {
        /**
         * @psalm-suppress TooManyTemplateParams
         */
        return $this->locks->has($lock->getKey())->then(function (bool $has) use ($lock) {
            if ($has) {
                return $this->locks->get($lock->getKey());
            }

            return FALSE_;
        })->then(
            /** @phpstan-ignore-next-line */
            function (?Lock $storedLock) use ($lock) {
                if ($storedLock instanceof Lock && $storedLock->getRng() === $lock->getRng()) {
                    return $this->locks->delete($lock->getKey());
                }

                return ! ($storedLock instanceof Lock) || $storedLock->getRng() === $lock->getRng();
            }
        );
    }
}
