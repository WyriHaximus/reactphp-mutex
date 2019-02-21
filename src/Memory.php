<?php declare(strict_types=1);

namespace WyriHaximus\React\Mutex;

use React\Promise\PromiseInterface;
use function React\Promise\resolve;

final class Memory implements MutexInterface
{
    private $locks = [];

    public function acquire(string $key): PromiseInterface
    {
        if (isset($this->locks[$key])) {
            return resolve(false);
        }

        $rng = \bin2hex(\random_bytes(13));
        $this->locks[$key] = new Lock($key, $rng);

        return resolve($this->locks[$key]);
    }

    public function release(Lock $lock): PromiseInterface
    {
        if (!isset($this->locks[$lock->getKey()])) {
            return resolve(false);
        }

        unset($this->locks[$lock->getKey()]);

        return resolve(true);
    }
}
