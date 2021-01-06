<?php

declare(strict_types=1);

namespace WyriHaximus\React\Mutex;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use WyriHaximus\AsyncTestUtilities\AsyncTestCase;

use function React\Promise\all;

abstract class AbstractMutexTestCase extends AsyncTestCase
{
    abstract public function provideMutex(LoopInterface $loop): MutexInterface;

    /**
     * @test
     */
    final public function thatYouCantRequiredTheSameLockTwice(): void
    {
        $loop  = Factory::create();
        $mutex = $this->provideMutex($loop);

        $firstLock         = '';
        $secondLock        = '';
        $firstMutexPromise = $mutex->acquire('key', 0.1);
        /** @phpstan-ignore-next-line */
        $firstMutexPromise->then(static function (?Lock $lock) use (&$firstLock): void {
            $firstLock = $lock;
        });
        $secondtMutexPromise = $mutex->acquire('key', 0.1);
        /** @phpstan-ignore-next-line */
        $secondtMutexPromise->then(static function (?Lock $lock) use (&$secondLock): void {
            $secondLock = $lock;
        });

        $this->await(all([$firstMutexPromise, $secondtMutexPromise]), $loop);

        self::assertInstanceOf(Lock::class, $firstLock);
        self::assertNull($secondLock);
    }
}
