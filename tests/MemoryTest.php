<?php declare(strict_types=1);

namespace WyriHaximus\Tests\React\Mutex;

use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\LoopInterface;
use WyriHaximus\React\Mutex\Memory;
use WyriHaximus\React\Mutex\MutexInterface;
use WyriHaximus\React\Mutex\MutexTestsTrait;

/**
 * @internal
 */
final class MemoryTest extends TestCase
{
    use MutexTestsTrait;

    public function provideMutex(LoopInterface $loop): MutexInterface
    {
        return new Memory();
    }
}
