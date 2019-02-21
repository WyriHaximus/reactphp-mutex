<?php declare(strict_types=1);

namespace WyriHaximus\React\Mutex;

use function Clue\React\Block\await;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use function React\Promise\all;

trait MutexTestsTrait
{
    abstract public function provideMutex(LoopInterface $loop): MutexInterface;

    public function testThatYouCantRequiredTheSameLockTwice(): void
    {
        $loop = Factory::create();
        $mutex = $this->provideMutex($loop);

        $firstLock = null;
        $secondLock = null;
        $firstMutexPromise = $mutex->acquire('key');
        $firstMutexPromise->done(function ($lock) use (&$firstLock): void {
            $firstLock = $lock;
        });
        $secondtMutexPromise = $mutex->acquire('key');
        $secondtMutexPromise->done(function ($lock) use (&$secondLock): void {
            $secondLock = $lock;
        });

        await(all($firstMutexPromise, $secondtMutexPromise), $loop, 30);

        self::assertInstanceOf(Lock::class, $firstLock);
        self::assertFalse($secondLock);
    }
}
