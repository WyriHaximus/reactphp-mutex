<?php

declare(strict_types=1);

namespace WyriHaximus\React\Mutex;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;
use WyriHaximus\AsyncTestUtilities\AsyncTestCase;

use function React\Promise\all;
use function WyriHaximus\React\timedPromise;

use const WyriHaximus\Constants\Numeric\ONE;
use const WyriHaximus\Constants\Numeric\ONE_FLOAT;
use const WyriHaximus\Constants\Numeric\TWO_FLOAT;

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
        $firstMutexPromise = $mutex->acquire('key', TWO_FLOAT);
        /** @phpstan-ignore-next-line */
        $firstMutexPromise->then(static function (?Lock $lock) use (&$firstLock): void {
            $firstLock = $lock;
        });
        $secondtMutexPromise = timedPromise($loop, ONE)->then(
            static fn (): PromiseInterface => $mutex->acquire('key', TWO_FLOAT)
        );
        /** @phpstan-ignore-next-line */
        $secondtMutexPromise->then(static function (?Lock $lock) use (&$secondLock): void {
            $secondLock = $lock;
        });

        $this->await(all([$firstMutexPromise, $secondtMutexPromise]), $loop);

        self::assertInstanceOf(Lock::class, $firstLock);
        self::assertNull($secondLock);
    }

    /**
     * @test
     */
    final public function cannotReleaseLockWithWrongRng(): void
    {
        $loop  = Factory::create();
        $mutex = $this->provideMutex($loop);

        $fakeLock = new Lock('key', 'rng');
        $mutex->acquire('key', ONE_FLOAT);

        $result = $this->await($mutex->release($fakeLock), $loop);
        self::assertFalse($result);
    }
}
