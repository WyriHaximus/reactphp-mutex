<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\React\Mutex;

use React\EventLoop\LoopInterface;
use WyriHaximus\React\Mutex\AbstractMutexTestCase;
use WyriHaximus\React\Mutex\Memory;
use WyriHaximus\React\Mutex\MutexInterface;

final class MemoryTest extends AbstractMutexTestCase
{
    public function provideMutex(LoopInterface $loop): MutexInterface
    {
        return new Memory();
    }
}
