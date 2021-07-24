<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\React\Mutex;

use WyriHaximus\React\Mutex\AbstractMutexTestCase;
use WyriHaximus\React\Mutex\Contracts\MutexInterface;
use WyriHaximus\React\Mutex\Memory;

final class MemoryTest extends AbstractMutexTestCase
{
    public function provideMutex(): MutexInterface
    {
        return new Memory();
    }
}
