<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\React\Mutex;

use WyriHaximus\AsyncTestUtilities\AsyncTestCase;
use WyriHaximus\React\Mutex\Lock;

final class LockTest extends AsyncTestCase
{
    /**
     * @test
     */
    public function getters(): void
    {
        $key  = 'key';
        $rng  = 'rng';
        $lock = new Lock($key, $rng);

        self::assertSame($key, $lock->getKey());
        self::assertSame($rng, $lock->getRng());
    }
}
