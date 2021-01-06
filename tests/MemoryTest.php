<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\React\Mutex;

use React\EventLoop\LoopInterface;
use WyriHaximus\AsyncTestUtilities\AsyncTestCase;
use WyriHaximus\React\Mutex\Memory;
use WyriHaximus\React\Mutex\MutexInterface;
use WyriHaximus\React\Mutex\MutexTestsTrait;

final class MemoryTest extends AsyncTestCase
{
    use MutexTestsTrait;

    public function provideMutex(LoopInterface $loop): MutexInterface
    {
        return new Memory();
    }
}
