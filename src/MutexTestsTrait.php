<?php

declare(strict_types=1);

namespace WyriHaximus\React\Mutex;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

use function React\Promise\all;

trait MutexTestsTrait
{
    abstract public function provideMutex(LoopInterface $loop): MutexInterface;

    /**
     * @test
     */
    public function thatYouCantRequiredTheSameLockTwice(): void
    {
        $loop  = Factory::create();
        $mutex = $this->provideMutex($loop);

        $firstLock         = '';
        $secondLock        = '';
        $firstMutexPromise = $mutex->acquire('key', 0.1);
        $firstMutexPromise->then(static function ($lock) use (&$firstLock): void {
            $firstLock = $lock;
        });
        $secondtMutexPromise = $mutex->acquire('key', 0.1);
        $secondtMutexPromise->then(static function ($lock) use (&$secondLock): void {
            $secondLock = $lock;
        });

        $this->await(all([$firstMutexPromise, $secondtMutexPromise]), $loop);

        self::assertInstanceOf(Lock::class, $firstLock);
        self::assertNull($secondLock);
    }
}
