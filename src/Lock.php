<?php

declare(strict_types=1);

namespace WyriHaximus\React\Mutex;

use WyriHaximus\React\Mutex\Contracts\LockInterface;

final class Lock implements LockInterface
{
    public function __construct(private string $key, private string $rng)
    {
    }

    public function key(): string
    {
        return $this->key;
    }

    public function rng(): string
    {
        return $this->rng;
    }
}
