<?php

declare(strict_types=1);

namespace WyriHaximus\React\Mutex;

use React\Cache\ArrayCache;
use React\Cache\CacheInterface;
use React\Promise\PromiseInterface;

use function bin2hex;
use function random_bytes;

final class Memory implements MutexInterface
{
    private const RANDOM_BYTES_LENGTH = 13;
    private CacheInterface $locks;

    public function __construct()
    {
        $this->locks = new ArrayCache();
    }

    public function acquire(string $key): PromiseInterface
    {
        /**
         * @phpstan-ignore-next-line
         * @psalm-suppress TooManyTemplateParams
         */
        return $this->locks->has($key)->then(function (bool $has) use ($key): ?Lock {
            if ($has) {
                return null;
            }

            $rng  = bin2hex(random_bytes(self::RANDOM_BYTES_LENGTH));
            $lock = new Lock($key, $rng);
            $this->locks->set($key, $lock);

            return $lock;
        });
    }

    public function release(Lock $lock): PromiseInterface
    {
        /**
         * @psalm-suppress TooManyTemplateParams
         */
        return $this->locks->has($lock->getKey());
    }
}
